<?php

namespace app\models\admin;

use app\core\Model;
use app\models\DanhMucModel;
use app\models\entities\DanhMuc;
use PDO;

class AdminCategoryBrandModel extends Model
{
    // =========================================================
    // DANH MỤC (CATEGORY)
    // =========================================================

    public function getAllCategories(): array
    {
        $sql = "SELECT dm.*, 
                (SELECT COUNT(*) FROM san_pham sp WHERE sp.ma_danh_muc = dm.id AND sp.ngay_xoa IS NULL) as so_san_pham
                FROM danh_muc dm 
                WHERE dm.ngay_xoa IS NULL 
                ORDER BY dm.id DESC";
        $stmt = $this->conn->query($sql);
        $categories = $stmt->fetchAll();
        $data = [];
        foreach ($categories as $index => $dong) {
            $data[] = new DanhMuc($dong);
        }
        return $data;
    }

    public function getCategoryById($id): ?DanhMuc
    {
        $stmt = $this->conn->prepare("SELECT * FROM danh_muc WHERE id = ? AND ngay_xoa IS NULL");
        $stmt->execute([$id]);
        $dong = $stmt->fetch();
        if (!$dong) {
            return null;
        }
        return new DanhMuc($dong);
    }

    /** Kiểm tra trùng tên danh mục (bỏ qua id hiện tại khi cập nhật) */
    public function findCategoryByName($ten, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM danh_muc WHERE ten_danh_muc = ? AND id != ? AND ngay_xoa IS NULL");
            $stmt->execute([$ten, $excludeId]);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM danh_muc WHERE ten_danh_muc = ? AND ngay_xoa IS NULL");
            $stmt->execute([$ten]);
        }
        return $stmt->fetch();
    }

    /** Kiểm tra trùng slug danh mục */
    public function findCategoryBySlug($slug, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM danh_muc WHERE duong_dan_slug = ? AND id != ? AND ngay_xoa IS NULL");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM danh_muc WHERE duong_dan_slug = ? AND ngay_xoa IS NULL");
            $stmt->execute([$slug]);
        }
        return  $stmt->fetch();
    }

    /** Đếm sản phẩm đang dùng danh mục này (để chặn xóa) */
    public function countProductsByCategory($categoryId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM san_pham WHERE ma_danh_muc = ? AND ngay_xoa IS NULL");
        $stmt->execute([$categoryId]);
        return (int) $stmt->fetchColumn();
    }

    /** Thêm mới danh mục - Có hình ảnh */
    public function insertCategory($ten, $slug, $trangthai, $hinh_anh = null)
    {
        $stmt = $this->conn->prepare(
            "SELECT id FROM danh_muc WHERE duong_dan_slug = ? AND ngay_xoa IS NOT NULL"
        );
        $stmt->execute([$slug]);
        $deletedCategory = $stmt->fetch();

        if ($deletedCategory) {
            $stmt = $this->conn->prepare(
                "UPDATE danh_muc 
             SET ten_danh_muc = ?, trang_thai = ?, hinh_anh = ?, ngay_xoa = NULL 
             WHERE id = ?"
            );
            return $stmt->execute([$ten, $trangthai, $hinh_anh, $deletedCategory['id']]);
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO danh_muc (ten_danh_muc, duong_dan_slug, trang_thai, hinh_anh) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$ten, $slug, $trangthai, $hinh_anh]);
    }

    /** Cập nhật danh mục - Có hình ảnh */
    public function updateCategory($id, $ten, $slug, $trangthai, $hinh_anh = null)
    {
        $stmt = $this->conn->prepare(
            "UPDATE danh_muc SET ten_danh_muc = ?, duong_dan_slug = ?, trang_thai = ?, hinh_anh = ? WHERE id = ? AND ngay_xoa IS NULL"
        );
        return $stmt->execute([$ten, $slug, $trangthai, $hinh_anh, $id]);
    }

    /** Xóa mềm danh mục - chỉ cho phép khi không còn sản phẩm */
    public function xoaMemCategory($id)
    {
        $stmt = $this->conn->prepare(
            "UPDATE danh_muc SET trang_thai = 0, ngay_xoa = NOW() WHERE id = ? AND ngay_xoa IS NULL"
        );
        return $stmt->execute([$id]);
    }

    // =========================================================
    // THƯƠNG HIỆU (BRAND)
    // =========================================================

    public function getAllBrands()
    {
        $stmt = $this->conn->query("SELECT * FROM thuong_hieu ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function insertBrand($ten, $hinh_anh, $mota)
    {
        $stmt = $this->conn->prepare("INSERT INTO thuong_hieu (ten_thuong_hieu, hinh_anh, mo_ta) VALUES (?, ?, ?)");
        return $stmt->execute([$ten, $hinh_anh, $mota]);
    }
    public function editBrand() {}
}
