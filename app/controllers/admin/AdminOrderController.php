<?php

namespace app\controllers\admin;

use app\models\admin\AdminOrderModel;

class AdminOrderController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminOrderModel();
    }

    public function index(): array
    {
        $orders = $this->model->getAllOrders();

        return [
            'title' => 'Quản lý Đơn hàng | Admin',
            'view' => 'admin/order/index.php',
            'orders' => $orders
        ];
    }

    public function detail(): array
    {
        $id = $_GET['id'] ?? 0;
        
        $order = $this->model->getOrderById($id);

        if (!$order) {
            header("Location: ?page=admin-orders");
            exit;
        }

        $items = $this->model->getOrderItems($id);

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
            
            $this->model->updateOrderStatus($id, $status);
            
            header("Location: ?page=admin-order-detail&id=" . $id);
            exit;
        }
    }
}
