<?php

namespace app\controllers\admin;

use app\models\admin\AdminCategoryBrandModel;

class AdminCategoryBrandController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminCategoryBrandModel();
    }

    public function categories(): array
    {
        $categories = $this->model->getAllCategories();

        return [
            'title' => 'Quản lý Danh mục | Admin',
            'view' => 'admin/category/index.php',
            'categories' => $categories
        ];
    }

    public function storeCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten = $_POST['ten_danh_muc'];
            $slug = $_POST['duong_dan'];
            $trangthai = isset($_POST['trang_thai']) ? 1 : 0;
            
            $this->model->insertCategory($ten, $slug, $trangthai);
            
            header("Location: ?page=admin-categories");
            exit;
        }
    }

    public function brands(): array
    {
        $brands = $this->model->getAllBrands();

        return [
            'title' => 'Quản lý Thương hiệu | Admin',
            'view' => 'admin/brand/index.php',
            'brands' => $brands
        ];
    }

    public function storeBrand()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten = $_POST['ten_thuong_hieu'];
            $mota = $_POST['mo_ta'] ?? '';
            // simplified image upload logic
            $hinh_anh = 'default-brand.png'; 

            $this->model->insertBrand($ten, $hinh_anh, $mota);
            
            header("Location: ?page=admin-brands");
            exit;
        }
    }
}
