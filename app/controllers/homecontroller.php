<?php

namespace app\controllers;

use app\models\SanPhamModel;

class HomeController
{
    private $sanPhamModel;
    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
    }
    public function index(): array
    {
        $sanPhamSale = $this->sanPhamModel->getSanPhamSale();
        $sanPhamMoi = $this->sanPhamModel->getSanPhamMoi();
        return [
            'title' => "Trang chủ | Bảo Đạt Sport",
            'sanPhamSale' => $sanPhamSale,
            'sanPhamMoi' => $sanPhamMoi,
        ];
    }
}
