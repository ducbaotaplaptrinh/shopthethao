<?php

namespace app\controllers;

use app\models\OrderModel;
use app\models\entities\DonHang;

class OrderController
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    public function checkout(): ?array
    {
        // Enforce login for checkout
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login&redirect=checkout");
            exit;
        }

        $cartItems = $_SESSION['cart'] ?? [];
        if (empty($cartItems)) {
            header("Location: ?page=cart");
            exit;
        }

        $totalPayment = 0;
        foreach ($cartItems as $item) {
            $totalPayment += $item['price'] * $item['qty'];
        }

        return [
            'title' => 'Thanh toán đơn hàng | Bảo Đạt Sport',
            'cartItems' => $cartItems,
            'totalPayment' => $totalPayment
        ];
    }

    public function place(): void
    {
        // Enforce login for placing orders
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login&redirect=checkout");
            exit;
        }

        $cartItems = $_SESSION['cart'] ?? [];
        if (empty($cartItems)) {
            header("Location: ?page=cart");
            exit;
        }

        $ho_ten = isset($_POST['ho_ten']) ? trim($_POST['ho_ten']) : '';
        $sdt = isset($_POST['so_dien_thoai']) ? trim($_POST['so_dien_thoai']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $dia_chi = isset($_POST['dia_chi']) ? trim($_POST['dia_chi']) : '';
        $ghi_chu = isset($_POST['ghi_chu']) ? trim($_POST['ghi_chu']) : '';
        $payment = isset($_POST['phuong_thuc_thanh_toan']) ? trim($_POST['phuong_thuc_thanh_toan']) : 'cod';

        // Validation
        if (empty($ho_ten) || empty($sdt) || empty($dia_chi)) {
            $_SESSION['order_error'] = 'Vui lòng điền đầy đủ các thông tin bắt buộc (*).';
            header("Location: ?page=checkout");
            exit;
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }
        
        $shipping = 0.00; // Free shipping
        $discount = 0.00;
        $total = $subtotal + $shipping - $discount;

        // Construct DonHang Entity
        $order = new DonHang();
        $order->setMa_nguoi_dung((int)$_SESSION['user']['id']);
        $order->setHo_ten_nguoi_nhan($ho_ten);
        $order->setSo_dien_thoai($sdt);
        $order->setEmail(!empty($email) ? $email : null);
        $order->setDia_chi_giao_hang($dia_chi);
        $order->setGhi_chu(!empty($ghi_chu) ? $ghi_chu : null);
        $order->setTong_tien_hang($subtotal);
        $order->setPhi_van_chuyen($shipping);
        $order->setTien_giam_gia($discount);
        $order->setTong_thanh_toan($total);
        $order->setPhuong_thuc_thanh_toan($payment);
        $order->setTrang_thai_thanh_toan($payment === 'cod' ? 'chua_thanh_toan' : 'da_thanh_toan');
        $order->setTrang_thai_don_hang('cho_xac_nhan');

        try {
            $orderCode = $this->orderModel->placeOrder($order, $cartItems);
            
            // Gửi email hóa đơn cho khách hàng nếu có địa chỉ email
            $targetEmail = !empty($email) ? $email : ($_SESSION['user']['email'] ?? '');
            if (!empty($targetEmail)) {
                $orderDetails = $this->orderModel->getOrderDetails($orderCode);
                if ($orderDetails) {
                    \app\services\MailService::sendOrderInvoice(
                        $targetEmail,
                        $order->getHo_ten_nguoi_nhan(),
                        $orderDetails['order'],
                        $orderDetails['items']
                    );
                }
            }

            // Clear cart
            unset($_SESSION['cart']);
            
            header("Location: ?page=order-success&code=" . $orderCode);
            exit;
        } catch (\Exception $e) {
            $_SESSION['order_error'] = 'Đã xảy ra lỗi trong quá trình đặt hàng: ' . $e->getMessage();
            header("Location: ?page=checkout");
            exit;
        }
    }

    public function success(): ?array
    {
        $code = $_GET['code'] ?? '';
        if (empty($code)) {
            header("Location: ?page=home");
            exit;
        }

        $details = $this->orderModel->getOrderDetails($code);
        if (!$details) {
            header("Location: ?page=home");
            exit;
        }

        return [
            'title' => 'Đặt hàng thành công | Bảo Đạt Sport',
            'order' => $details['order'],
            'items' => $details['items']
        ];
    }

    public function track(): array
    {
        $term = $_GET['term'] ?? '';
        $orders = [];
        if (!empty($term)) {
            $orders = $this->orderModel->trackOrder($term);
        }

        return [
            'title' => 'Tra cứu đơn hàng | Bảo Đạt Sport',
            'term' => $term,
            'orders' => $orders
        ];
    }

    public function myOrders(): array
    {
        // Bắt buộc phải đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login&redirect=my-orders");
            exit;
        }

        $userId    = (int)$_SESSION['user']['id'];
        $activeTab = $_GET['status'] ?? 'all';

        // Chỉ cho phép các giá trị hợp lệ — đồng bộ với bộ trạng thái admin
        $validStatuses = ['all', 'cho_xac_nhan', 'dang_xu_ly', 'dang_giao', 'hoan_thanh', 'da_huy'];
        if (!in_array($activeTab, $validStatuses)) {
            $activeTab = 'all';
        }

        $filterStatus = $activeTab === 'all' ? '' : $activeTab;
        $orders       = $this->orderModel->getOrdersByUser($userId, $filterStatus);
        $statusCounts = $this->orderModel->countOrdersByStatus($userId);

        return [
            'title'        => 'Đơn hàng của tôi | Bảo Đạt Sport',
            'orders'       => $orders,
            'statusCounts' => $statusCounts,
            'activeTab'    => $activeTab,
        ];
    }
}
