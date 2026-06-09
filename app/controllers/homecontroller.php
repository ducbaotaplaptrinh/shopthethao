<?php

namespace app\controllers;

use app\models\SanPhamModel;
use app\models\DanhMucModel;

class HomeController
{
    private $sanPhamModel;
    private $danhMucModel;

    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
        $this->danhMucModel = new DanhMucModel();
    }
    public function index(): array
    {
        $sanPhamSale = $this->sanPhamModel->getSanPhamSale();
        $sanPhamMoi = $this->sanPhamModel->getSanPhamMoi();
        $danhMucSanPham = $this->danhMucModel->getDanhSachDanhMuc();
        return [
            'title' => "Trang chủ | Bảo Đạt Sport",
            'sanPhamSale' => $sanPhamSale,
            'sanPhamMoi' => $sanPhamMoi,
            'danhSachDanhMuc' =>  $danhMucSanPham,
        ];
    }
}
