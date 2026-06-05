<?php

namespace app\controllers;

use app\models\SanPhamModel;

class SanPhamController
{
    private $sanPhamModel;
    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
    }
    //Hien thi danh sach san pham 
    private function index(): array
    {
        $danhSach = $this->sanPhamModel->getDanhSachSanPham();
        return ['danhsachsp' => $danhSach];
    }
    private function chitiet(): array
    {
        $slug = $_GET['slug'] ?? '';
        $sanPham = $this->sanPhamModel->getSanPhamTheoSlug($slug);
        if (!$sanPham) {
            header("location: ?page=404");
            exit;
        }
        return ['sanpham' => $sanPham];
    }
}
