<?php

namespace app\controllers\admin;

use app\core\Model;
use PDO;

class AdminOrderController extends Model
{

    public function index(): array
    {
        $sql = "SELECT dh.*, nd.ho_ten, nd.email 
                FROM don_hang dh 
                LEFT JOIN nguoi_dung nd ON dh.id_nguoi_dung = nd.id 
                ORDER BY dh.id DESC";
        $stmt = $this->conn->prepare($sql);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'title' => 'Quản lý Đơn hàng | Admin',
            'view' => 'admin/order/index.php',
            'orders' => $orders
        ];
    }

    public function detail(): array
    {
        $id = $_GET['id'] ?? 0;

        // Lấy thông tin đơn hàng
        $sqlOrder = "SELECT dh.*, nd.ho_ten, nd.email, nd.so_dien_thoai as sdt_user
                     FROM don_hang dh 
                     LEFT JOIN nguoi_dung nd ON dh.id_nguoi_dung = nd.id 
                     WHERE dh.id = ?";
        $stmtOrder = $this->conn->prepare($sqlOrder);
        $stmtOrder->execute([$id]);
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            header("Location: ?page=admin-orders");
            exit;
        }

        // Lấy chi tiết sản phẩm trong đơn
        $sqlItems = "SELECT ct.*, sp.ten_san_pham, bt.ma_vach_sku 
                     FROM chi_tiet_don_hang ct
                     LEFT JOIN bien_the_san_pham bt ON ct.id_bien_the_san_pham = bt.id
                     LEFT JOIN san_pham sp ON bt.id_san_pham = sp.id
                     WHERE ct.id_don_hang = ?";
        $stmtItems = $this->conn->prepare($sqlItems);
        $stmtItems->execute([$id]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        return [
            'title' => 'Chi tiết Đơn hàng #' . $order['ma_don_hang'] . ' | Admin',
            'view' => 'admin/order/detail.php',
            'order' => $order,
            'items' => $items
        ];
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $status = $_POST['trang_thai_don_hang'];

            $stmt = $this->conn->prepare("UPDATE don_hang SET trang_thai_don_hang = ? WHERE id = ?");
            $stmt->execute([$status, $id]);

            header("Location: ?page=admin-order-detail&id=" . $id);
            exit;
        }
    }
}
