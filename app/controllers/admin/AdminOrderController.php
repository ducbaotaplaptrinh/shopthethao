<?php

namespace app\controllers\admin;

use app\models\admin\AdminOrderModel;

class AdminOrderController
{
    private $model;

    /**
     * STATE MACHINE: Định nghĩa các chuyển trạng thái HỢP LỆ.
     * Key   = trạng thái hiện tại
     * Value = mảng các trạng thái được phép chuyển sang
     */
    private const TRANG_THAI_CHO_PHEP = [
        'cho_xac_nhan' => ['dang_xu_ly', 'da_huy'],
        'dang_xu_ly'   => ['dang_giao',  'da_huy'],
        'dang_giao'    => ['hoan_thanh', 'da_huy'],
        'hoan_thanh'   => [],   // Đơn hoàn thành: không thể thay đổi
        'da_huy'       => [],   // Đơn đã hủy: không thể thay đổi
    ];

    public function __construct()
    {
        $this->model = new AdminOrderModel();
    }

    // Kiểm tra quyền admin
    private function kiemTraAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'quan_tri') {
            header("Location: ?page=login");
            exit;
        }
    }

    public function index(): array
    {
        $this->kiemTraAdmin();
        $orders = $this->model->getAllOrders();

        return [
            'title'  => 'Quản lý Đơn hàng | Admin',
            'view'   => 'admin/order/index.php',
            'orders' => $orders
        ];
    }

    public function detail(): array
    {
        $this->kiemTraAdmin();
        $id = $_GET['id'] ?? 0;
        
        $order = $this->model->getOrderById($id);

        if (!$order) {
            header("Location: ?page=admin-orders");
            exit;
        }

        $items = $this->model->getOrderItems($id);

        // Truyền danh sách trạng thái có thể chuyển đến view
        $trangThaiHienTai  = $order['trang_thai_don_hang'];
        $trangThaiCoTheChon = self::TRANG_THAI_CHO_PHEP[$trangThaiHienTai] ?? [];

        return [
            'title'               => 'Chi tiết Đơn hàng #' . $order['ma_don_hang'] . ' | Admin',
            'view'                => 'admin/order/detail.php',
            'order'               => $order,
            'items'               => $items,
            'trangThaiCoTheChon'  => $trangThaiCoTheChon,
        ];
    }

    // Cập nhật trạng thái đơn hàng với State Machine + Inventory
    public function capNhatTrangThaiDonHang()
    {
        $this->kiemTraAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=admin-orders");
            exit;
        }

        $id          = intval($_POST['id'] ?? 0);
        $trangThaiMoi = trim($_POST['trang_thai_don_hang'] ?? '');

        if ($id <= 0 || !array_key_exists($trangThaiMoi, self::TRANG_THAI_CHO_PHEP)) {
            header("Location: ?page=admin-orders&error=invalid_request");
            exit;
        }

        // Lấy đơn hàng hiện tại
        $donHang = $this->model->getOrderById($id);
        if (!$donHang) {
            header("Location: ?page=admin-orders&error=not_found");
            exit;
        }

        $trangThaiHienTai = $donHang['trang_thai_don_hang'];

        // === STATE MACHINE VALIDATION ===
        // Kiểm tra trạng thái mới có nằm trong danh sách cho phép không
        $chuyenHopLe = self::TRANG_THAI_CHO_PHEP[$trangThaiHienTai] ?? [];
        if (!in_array($trangThaiMoi, $chuyenHopLe)) {
            // Không cho phép nhảy bậc hoặc sửa đơn đã kết thúc
            header("Location: ?page=admin-order-detail&id={$id}&error=invalid_transition");
            exit;
        }
        // ================================

        try {
            $this->model->capNhatTrangThaiDonHang($id, $trangThaiMoi, $trangThaiHienTai);
            header("Location: ?page=admin-order-detail&id={$id}&success=updated");
        } catch (\Exception $e) {
            // Lỗi khi trừ kho (không đủ hàng)
            $msg = urlencode($e->getMessage());
            header("Location: ?page=admin-order-detail&id={$id}&error=stock_error&msg={$msg}");
        }
        exit;
    }

    // Xóa đơn hàng (chỉ cho phép xóa đơn hàng đã hủy hoặc đơn chờ xác nhận)
    public function xoaDonHang()
    {
        $this->kiemTraAdmin();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: ?page=admin-orders&error=invalid_id");
            exit;
        }

        $donHang = $this->model->getOrderById($id);
        if (!$donHang) {
            header("Location: ?page=admin-orders&error=not_found");
            exit;
        }

        // Ràng buộc: chỉ xóa được đơn đã hủy hoặc chờ xác nhận
        $trangThaiDuocXoa = ['da_huy', 'cho_xac_nhan'];
        if (!in_array($donHang['trang_thai_don_hang'], $trangThaiDuocXoa)) {
            header("Location: ?page=admin-orders&error=cannot_delete");
            exit;
        }

        $this->model->xoaDonHang($id);
        header("Location: ?page=admin-orders&success=deleted");
        exit;
    }
}
