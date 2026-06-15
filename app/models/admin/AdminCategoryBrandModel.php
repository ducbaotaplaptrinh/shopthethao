<?php

namespace app\models\admin;

use app\core\Model;
use PDO;

class AdminCategoryBrandModel extends Model
{
    public function getAllCategories()
    {
        $stmt = $this->conn->query("SELECT * FROM danh_muc ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertCategory($ten, $slug, $trangthai)
    {
        $stmt = $this->conn->prepare("INSERT INTO danh_muc (ten_danh_muc, duong_dan, trang_thai) VALUES (?, ?, ?)");
        return $stmt->execute([$ten, $slug, $trangthai]);
    }

    public function getAllBrands()
    {
        $stmt = $this->conn->query("SELECT * FROM thuong_hieu ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertBrand($ten, $hinh_anh, $mota)
    {
        $stmt = $this->conn->prepare("INSERT INTO thuong_hieu (ten_thuong_hieu, hinh_anh, mo_ta) VALUES (?, ?, ?)");
        return $stmt->execute([$ten, $hinh_anh, $mota]);
    }
}
