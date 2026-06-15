<?php

namespace app\controllers\admin;

use app\models\admin\AdminProductModel;

class AdminProductController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminProductModel();
    }

    public function index(): array
    {
        $products = $this->model->getAllProducts();

        return [
            'title' => 'Quản lý Sản phẩm | Admin',
            'view' => 'admin/product/index.php',
            'products' => $products
        ];
    }

    public function create(): array
    {
        $categories = $this->model->getCategoriesForDropdown();
        $brands = $this->model->getBrandsForDropdown();
        $attributes = $this->model->getVariantAttributes();

        return [
            'title' => 'Thêm Sản phẩm mới | Admin',
            'view' => 'admin/product/form.php',
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes
        ];
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->model->insertProductWithVariants($_POST, $_POST['variants'] ?? '');
                header("Location: ?page=admin-products");
                exit;
            } catch (\Exception $e) {
                die("Error saving product: " . $e->getMessage());
            }
        }
    }
}
