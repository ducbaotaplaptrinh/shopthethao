<?php

namespace app\controllers\admin;

use app\models\admin\AdminDashboardModel;

class AdminDashboardController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminDashboardModel();
    }

    public function index(): array
    {
        $stats = $this->model->getThongKe();
        $chart = $this->model->getDoanhThu7Ngay();

        return [
            'title' => 'Dashboard | Quản trị',
            'view' => 'admin/dashboard.php',
            'stats' => $stats,
            'chartLabels' => json_encode($chart['labels']),
            'chartData' => json_encode($chart['data'])
        ];
    }
}
