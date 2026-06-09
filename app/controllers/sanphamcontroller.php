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
    public function index(): array
    {
        $slugDM = $_GET['category'] ?? '';
        $slugTH = $_GET['brand'] ?? '';
        if (isset($slugDM) && isset($slugTH)) {
            $danhSach = $this->sanPhamModel->getSPTheoDanhMucThuongHieu($slugDM, $slugTH);
        }

        if (!empty($danhSach) && isset($danhSach[0]['tenThuongHieu'])) {
            $tenTH = $danhSach[0]['tenThuongHieu'];
            $tenDM = $danhSach[0]['tenDanhMuc'];
            $title = $tenDM . ' ' . $tenTH . " | Bảo Đạt Sport";
        } else {
            $title = "Danh sách sản phẩm";
        }


        return [
            'title' => $title,
            'danhSachSanPham' => $danhSach
        ];
    }
    public function chitiet(): ?array
    {
        $slug = $_GET['slug'] ?? '';
        $sanPham = $this->sanPhamModel->getChiTietSanPham($slug);
        if (!$sanPham) {
            return null;
        }
        return [
            'title' => $sanPham['item']->getTen_san_pham() . "| Bảo Đạt Sport",
            'sanpham' => $sanPham['item'],
            'tenDanhMuc' => $sanPham['tenDanhMuc'],
            'tenThuongHieu' => $sanPham['tenThuongHieu'],
        ];
    }
}
