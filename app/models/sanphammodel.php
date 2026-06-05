<?php

namespace app\models;

require BASE_PATH . "/app/core/Model.php";

use app\core\Model;
use app\models\entities\SanPham;
use PDO;


class SanPhamModel extends Model
{
    public function getDanhSachSanPham()
    {
        $sql = "SELECT * FROM san_pham WHERE ngay_xoa is null";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $dulieu = $stmt->fetchAll();
        if (!$dulieu) {
            return null;
        }
        $danhSachEntities = [];
        foreach ($dulieu as $dong) {
            $danhSachEntities[] = new SanPham($dong);
        }
        return $danhSachEntities;
    }
    public function getChiTietSanPham($slugSanPham): ?array
    {
        $sql = "select d.ten_danh_muc, t.ten_thuong_hieu, s.* from san_pham s 
                join thuong_hieu t 
                on s.ma_thuong_hieu = t.id
                join danh_muc d 
                on d.id = s.ma_danh_muc
                where s.duong_dan_slug = :slug 
                and s.ngay_xoa is null 
                and t.ngay_xoa is null 
                and d.ngay_xoa is null 
                and s.trang_thai = 1 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slugSanPham]);
        $data = $stmt->fetch();
        if (!$data) return null;

        return [
            'item' => new SanPham($data),
            'tenThuongHieu' => $data['ten_thuong_hieu'],
            'tenDanhMuc' => $data['ten_danh_muc'],
        ];;
    }
    public function getSanPhamTheoBrand($slugThuongHieu): ?array
    {
        $sql = "select d.ten_danh_muc, t.ten_thuong_hieu, s.* from san_pham s 
                join thuong_hieu t 
                on s.ma_thuong_hieu = t.id
                join danh_muc d 
                on d.id = s.ma_danh_muc
                where t.duong_dan_slug = :slug 
                and s.ngay_xoa is null 
                and t.ngay_xoa is null 
                and d.ngay_xoa is null 
                and s.trang_thai = 1 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slugThuongHieu]);
        $data = $stmt->fetchAll();
        $danhSachEntities = [];
        foreach ($data as $dong) {
            $danhSachEntities[] = [
                'item' => new SanPham($dong),
                'tenThuongHieu' => $dong['ten_thuong_hieu'],
                'tenDanhMuc' => $dong['ten_danh_muc'],
            ];
        }
        return $danhSachEntities;
    }
}
