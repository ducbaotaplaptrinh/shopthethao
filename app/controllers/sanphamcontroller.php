<?php

namespace app\controllers;

use app\models\SanPhamModel;
use app\models\GiaTriThuocTinhModel;
use app\models\ThuongHieuModel;
use app\models\DanhMucModel;

class SanPhamController
{
    private $sanPhamModel;
    private $giaTriThuocTinhModel;
    private $thuongHieuModel;
    private $danhMucModel;
    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
        $this->giaTriThuocTinhModel = new GiaTriThuocTinhModel();
        $this->thuongHieuModel = new ThuongHieuModel();
        $this->danhMucModel = new DanhMucModel();
    }
    //Hien thi danh sach san pham 
    public function index(): ?array
    {
        $slugDM = $_GET['category'] ?? '';
        $slugTH = $_GET['brand'] ?? '';
        $selectedAttrs = $_GET['attrs'] ?? [];
        if (!is_array($selectedAttrs)) {
            $selectedAttrs = [$selectedAttrs];
        }
        $sort = $_GET['sort'] ?? 'newest';
        $keyword = $_GET['keyword'] ?? '';
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $limit = 16; // 4 rows * 4 columns
        $offset = ($currentPage - 1) * $limit;

        // Fetch products and count
        $danhSachSanPham = $this->sanPhamModel->getFilteredProducts($slugDM, $slugTH, $selectedAttrs, $sort, $limit, $offset, $keyword);
        $totalProducts = $this->sanPhamModel->getFilteredProductsCount($slugDM, $slugTH, $selectedAttrs, $keyword);
        $totalPages = ceil($totalProducts / $limit);
        if ($totalPages < 1) {
            $totalPages = 1;
        }

        // Fetch filter data for sidebar
        if (!empty($slugDM)) {
            $danhSachGiaTri = $this->giaTriThuocTinhModel->getThuocTinhTheoDm($slugDM);
            $dsThuongHieu = $this->thuongHieuModel->getTHTheoDM($slugDM);
        } else {
            $danhSachGiaTri = [];
            $dsThuongHieu = $this->thuongHieuModel->getDanhSachThuongHieu();
        }

        $tenThuongHieu = !empty($slugTH) ? $this->thuongHieuModel->getThuongHieutheoslug($slugTH) : null;
        $tenDanhMuc = !empty($slugDM) ? $this->danhMucModel->getDanhMuctheoslug($slugDM) : null;
        $dsDanhMuc = $this->danhMucModel->getDanhSachDanhMuc();

        // SEO/Title logic
        if ($tenDanhMuc && $tenThuongHieu) {
            $title = $tenDanhMuc->getTen_danh_muc() . ' ' . $tenThuongHieu->getTen_thuong_hieu() . " | Bảo Đạt Sport";
        } elseif ($tenDanhMuc) {
            $title = $tenDanhMuc->getTen_danh_muc() . " | Bảo Đạt Sport";
        } elseif ($tenThuongHieu) {
            $title = $tenThuongHieu->getTen_thuong_hieu() . " | Bảo Đạt Sport";
        } else {
            $title = "Danh sách sản phẩm | Bảo Đạt Sport";
        }

        return [
            'title' => $title,
            'danhSachSanPham' => $danhSachSanPham,
            'danhSachGiaTri' => $danhSachGiaTri,
            'dsThuongHieu' => $dsThuongHieu,
            'dsDanhMuc' => $dsDanhMuc,
            'tenThuongHieuMD' => $tenThuongHieu,
            'tenDanhMucMD' => $tenDanhMuc,
            'slugDM' => $slugDM,
            'slugTH' => $slugTH,
            'selectedAttrs' => $selectedAttrs,
            'sort' => $sort,
            'keyword' => $keyword,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
        ];
    }
    public function chitiet(): ?array
    {
        $slug = $_GET['slug'] ?? '';
        $sanPhamData = $this->sanPhamModel->getChiTietSanPham($slug);
        if (!$sanPhamData) {
            return null;
        }

        $sanpham = $sanPhamData['item'];
        $idSP = $sanpham->getId();

        // Fetch related products (same category, limit 4, offset 0)
        $categorySlug = $sanPhamData['category_slug'] ?? '';
        $relatedProducts = $this->sanPhamModel->getFilteredProducts($categorySlug, '', [], 'newest', 5, 0);
        if (!empty($relatedProducts)) {
            $relatedProducts = array_filter($relatedProducts, function($p) use ($idSP) {
                return $p['item']->getId() !== $idSP;
            });
            // Slice to ensure exactly 4 products max after filtering out current product
            $relatedProducts = array_slice($relatedProducts, 0, 4);
        }

        // Fetch gallery images
        $gallery = $this->sanPhamModel->getAnhSanPham($idSP);

        // Fetch reviews
        $reviews = $this->sanPhamModel->getReviews($idSP);

        // Fetch variations
        $variations = $this->sanPhamModel->getBienTheSanPham($idSP);

        // Extract unique attributes
        $uniqueAttributes = [];
        foreach ($variations as $bt) {
            foreach ($bt['attributes'] as $attr) {
                $name = $attr['ten_thuoc_tinh'];
                $val = $attr['gia_tri'];
                $valId = $attr['gia_tri_id'];
                if (!isset($uniqueAttributes[$name])) {
                    $uniqueAttributes[$name] = [];
                }
                $uniqueAttributes[$name][$valId] = $val;
            }
        }

        return [
            'title' => $sanpham->getTen_san_pham() . " | Bảo Đạt Sport",
            'sanpham' => $sanpham,
            'tenDanhMuc' => $sanPhamData['tenDanhMuc'],
            'tenThuongHieu' => $sanPhamData['tenThuongHieu'],
            'gallery' => $gallery,
            'reviews' => $reviews,
            'variations' => $variations,
            'uniqueAttributes' => $uniqueAttributes,
            'relatedProducts' => $relatedProducts,
            'pageStyles' => ['assets/css/product-detail.css']
        ];
    }

    public function suggest(): void
    {
        $keyword = $_GET['keyword'] ?? '';
        $keyword = trim($keyword);
        
        $suggestions = [];
        if ($keyword !== '') {
            $products = $this->sanPhamModel->getSearchSuggestions($keyword);
            foreach ($products as $sp) {
                $hasSale = $sp->getGia_khuyen_mai() > 0;
                $suggestions[] = [
                    'id' => $sp->getId(),
                    'name' => $sp->getTen_san_pham(),
                    'slug' => $sp->getDuong_dan_slug(),
                    'image' => getProductImage($sp->getAnh_dai_dien()),
                    'price' => $hasSale ? $sp->getGia_khuyen_mai() : $sp->getGia_ban(),
                    'price_formatted' => formatVND($hasSale ? $sp->getGia_khuyen_mai() : $sp->getGia_ban()),
                    'old_price' => $hasSale ? $sp->getGia_ban() : null,
                    'old_price_formatted' => $hasSale ? formatVND($sp->getGia_ban()) : null,
                    'sale_percent' => $hasSale ? $sp->getPhanTramGiam() : 0
                ];
            }
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($suggestions);
        exit();
    }
}
