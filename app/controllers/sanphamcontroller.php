<?php

namespace app\controllers;

use app\models\SanPhamModel;

require BASE_PATH . "/app/models/sanphammodel.php";
class SanPhamController
{
    private $sanPhamModel;
    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
    }
    //Hien thi danh sach san pham 
    public function index(): array
    {
        $slug = $_GET['slug'] ?? '';
        $danhSach = $this->sanPhamModel->getSanPhamTheoBrand($slug);
        return [
            'danhsachsp' => $danhSach,
        ];
    }
    public function chitiet(): ?array
    {
        $slug = $_GET['slug'] ?? '';
        $sanPham = $this->sanPhamModel->getChiTietSanPham($slug);
        if (!$sanPham) {
            return null;
        }
        dd($sanPham);
        return [
            'title' => $sanPham['item']->getTen_san_pham() . "| Bảo Đạt Sport",
            'sanpham' => $sanPham['item'],
            'tenDanhMuc' => $sanPham['ten_danh_muc'],
            'tenThuongHieu' => $sanPham['ten_thuong_hieu'],
        ];
    }
}
