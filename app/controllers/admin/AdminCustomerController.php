<?php

namespace app\controllers\admin;

use app\models\admin\AdminCustomerModel;

class AdminCustomerController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminCustomerModel();
    }

    public function index(): array
    {
        $customers = $this->model->getAllCustomers();
        $tiers = $this->model->getAllTiers();

        return [
            'title' => 'Quản lý Khách hàng | Admin',
            'view' => 'admin/customer/index.php',
            'customers' => $customers,
            'tiers' => $tiers
        ];
    }

    public function toggleStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $status = $_POST['trang_thai'];

            $this->model->toggleCustomerStatus($id, $status);
            
            header("Location: ?page=admin-customers");
            exit;
        }
    }
}
