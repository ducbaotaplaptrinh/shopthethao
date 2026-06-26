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
                ORDER BY dm.thu_tu_sap_xep ASC, dm.id DESC";
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

    /** Thêm mới danh mục - Có hình ảnh và thứ tự sắp xếp */
    public function insertCategory($ten, $slug, $trangthai, $hinh_anh = null, $thu_tu = 0)
    {
        $stmt = $this->conn->prepare(
            "SELECT id FROM danh_muc WHERE duong_dan_slug = ? AND ngay_xoa IS NOT NULL"
        );
        $stmt->execute([$slug]);
        $deletedCategory = $stmt->fetch();

        if ($deletedCategory) {
            $stmt = $this->conn->prepare(
                "UPDATE danh_muc 
             SET ten_danh_muc = ?, trang_thai = ?, hinh_anh = ?, thu_tu_sap_xep = ?, ngay_xoa = NULL 
             WHERE id = ?"
            );
            return $stmt->execute([$ten, $trangthai, $hinh_anh, $thu_tu, $deletedCategory['id']]);
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO danh_muc (ten_danh_muc, duong_dan_slug, trang_thai, hinh_anh, thu_tu_sap_xep) VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$ten, $slug, $trangthai, $hinh_anh, $thu_tu]);
    }

    /** Cập nhật danh mục - Có hình ảnh và thứ tự sắp xếp */
    public function updateCategory($id, $ten, $slug, $trangthai, $hinh_anh = null, $thu_tu = 0)
    {
        $stmt = $this->conn->prepare(
            "UPDATE danh_muc SET ten_danh_muc = ?, duong_dan_slug = ?, trang_thai = ?, hinh_anh = ?, thu_tu_sap_xep = ? WHERE id = ? AND ngay_xoa IS NULL"
        );
        return $stmt->execute([$ten, $slug, $trangthai, $hinh_anh, $thu_tu, $id]);
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

    public function getAllBrands(): array
    {
        $stmt = $this->conn->query(
            "SELECT th.*, 
            (SELECT COUNT(*) FROM san_pham sp WHERE sp.ma_thuong_hieu = th.id AND sp.ngay_xoa IS NULL) as so_san_pham
            FROM thuong_hieu th 
            WHERE th.ngay_xoa IS NULL 
            ORDER BY th.id DESC"
        );
        return $stmt->fetchAll() ?: [];
    }

    public function insertBrand($ten, $slug, $anh_logo, $mota)
    {
        // Kiểm tra xem thương hiệu đã bị xóa mềm trước đó chưa để khôi phục
        $stmt = $this->conn->prepare("SELECT id FROM thuong_hieu WHERE duong_dan_slug = ? AND ngay_xoa IS NOT NULL");
        $stmt->execute([$slug]);
        $deleted = $stmt->fetch();

        if ($deleted) {
            $stmt = $this->conn->prepare(
                "UPDATE thuong_hieu 
                 SET ten_thuong_hieu = ?, anh_logo = ?, mo_ta = ?, trang_thai = 1, ngay_xoa = NULL 
                 WHERE id = ?"
            );
            return $stmt->execute([$ten, $anh_logo, $mota, $deleted['id']]);
        }

        $stmt = $this->conn->prepare("INSERT INTO thuong_hieu (ten_thuong_hieu, duong_dan_slug, anh_logo, mo_ta, trang_thai) VALUES (?, ?, ?, ?, 1)");
        return $stmt->execute([$ten, $slug, $anh_logo, $mota]);
    }

    public function getBrandById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM thuong_hieu WHERE id = ? AND ngay_xoa IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findBrandByName($ten, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM thuong_hieu WHERE ten_thuong_hieu = ? AND id != ? AND ngay_xoa IS NULL");
            $stmt->execute([$ten, $excludeId]);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM thuong_hieu WHERE ten_thuong_hieu = ? AND ngay_xoa IS NULL");
            $stmt->execute([$ten]);
        }
        return $stmt->fetch() ?: null;
    }

    public function findBrandBySlug($slug, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM thuong_hieu WHERE duong_dan_slug = ? AND id != ? AND ngay_xoa IS NULL");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM thuong_hieu WHERE duong_dan_slug = ? AND ngay_xoa IS NULL");
            $stmt->execute([$slug]);
        }
        return $stmt->fetch() ?: null;
    }

    public function countProductsByBrand($brandId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM san_pham WHERE ma_thuong_hieu = ? AND ngay_xoa IS NULL");
        $stmt->execute([$brandId]);
        return (int)$stmt->fetchColumn();
    }

    public function updateBrand($id, $ten, $slug, $anh_logo, $mota, $trang_thai)
    {
        $stmt = $this->conn->prepare(
            "UPDATE thuong_hieu 
             SET ten_thuong_hieu = ?, duong_dan_slug = ?, anh_logo = ?, mo_ta = ?, trang_thai = ? 
             WHERE id = ? AND ngay_xoa IS NULL"
        );
        return $stmt->execute([$ten, $slug, $anh_logo, $mota, $trang_thai, $id]);
    }

    public function xoaMemBrand($id)
    {
        $stmt = $this->conn->prepare(
            "UPDATE thuong_hieu SET trang_thai = 0, ngay_xoa = NOW() WHERE id = ? AND ngay_xoa IS NULL"
        );
        return $stmt->execute([$id]);
    }
}
