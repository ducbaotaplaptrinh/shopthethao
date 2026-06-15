<?php

namespace app\controllers\admin;

use app\core\Model;
use PDO;

class AdminCategoryBrandController extends Model
{

    public function categories(): array
    {
        $stmt = $this->conn->query("SELECT * FROM danh_muc ORDER BY id DESC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

            $stmt = $this->conn->prepare("INSERT INTO danh_muc (ten_danh_muc, duong_dan, trang_thai) VALUES (?, ?, ?)");
            $stmt->execute([$ten, $slug, $trangthai]);

            header("Location: ?page=admin-categories");
            exit;
        }
    }

    public function brands(): array
    {
        $stmt = $this->conn->query("SELECT * FROM thuong_hieu ORDER BY id DESC");
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

            $stmt = $this->conn->prepare("INSERT INTO thuong_hieu (ten_thuong_hieu, hinh_anh, mo_ta) VALUES (?, ?, ?)");
            $stmt->execute([$ten, $hinh_anh, $mota]);

            header("Location: ?page=admin-brands");
            exit;
        }
    }
}
