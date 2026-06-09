<?php

namespace app\models;

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
                and s.trang_thai = 1";
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
    public function getSPTheoDanhMucThuongHieu($slugDM, $slugTH)
    {
        $sql = "select d.ten_danh_muc, t.ten_thuong_hieu, s.* from san_pham s 
                join thuong_hieu t 
                on s.ma_thuong_hieu = t.id
                join danh_muc d 
                on d.id = s.ma_danh_muc
                where t.duong_dan_slug = :slug_th 
                and d.duong_dan_slug = :slug_dm 
                and s.ngay_xoa is null 
                and t.ngay_xoa is null 
                and d.ngay_xoa is null 
                and s.trang_thai = 1 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug_th' => $slugTH, 'slug_dm' => $slugDM]);
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

    public function getSPTheoDanhMuc($slugDM)
    {
        $sql = "select d.ten_danh_muc, t.ten_thuong_hieu, s.* from san_pham s 
                join thuong_hieu t 
                on s.ma_thuong_hieu = t.id
                join danh_muc d 
                on d.id = s.ma_danh_muc
                where  d.duong_dan_slug = :slug_dm 
                and s.ngay_xoa is null 
                and t.ngay_xoa is null 
                and d.ngay_xoa is null 
                and s.trang_thai = 1 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug_dm' => $slugDM]);
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
    public function getDanhMucThuongHieu()
    {
        $sql = "select distinct ten_danh_muc, ten_thuong_hieu, d.duong_dan_slug as slug_dm , th.duong_dan_slug as slug_th
                from danh_muc d
                join san_pham s on s.ma_danh_muc = d.id
                join thuong_hieu th on th.id = s.ma_thuong_hieu
                where s.trang_thai = 1 
                and th.trang_thai = 1 
                and d.ngay_xoa is null 
                and th.ngay_xoa is null 
                order by ma_danh_muc_cha,ten_danh_muc ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $danhSachEntities = [];
        foreach ($data as $dong) {
            $danhSachEntities[] = [
                'slugDM' => $dong['slug_dm'],
                'slugTH' => $dong['slug_th'],
                'tenThuongHieu' => $dong['ten_thuong_hieu'],
                'tenDanhMuc' => $dong['ten_danh_muc'],
            ];
        }
        return $danhSachEntities;
    }
    public function getSanPhamSale()
    {
        $sql = "select *
                from san_pham
                where gia_khuyen_mai > 0 and trang_thai = 1 and ngay_xoa is null";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $danhSachEntities = [];
        foreach ($data as $dong) {
            $danhSachEntities[] = new SanPham($dong);
        }
        return $danhSachEntities;
    }
    public function getSanPhamMoi()
    {
        $sql = "select ten_danh_muc, s.*
                from san_pham s join danh_muc d on d.id = s.ma_danh_muc
                where la_noi_bat = 1
                and ma_danh_muc_cha is not null 
                and s.trang_thai = 1 
                and s.ngay_xoa is null 
                and d.ngay_xoa is null 
                order by ma_danh_muc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $danhSachEntities = [];
        foreach ($data as $dong) {
            $danhSachEntities[] = [
                'item' =>  new SanPham($dong),
                'tenDanhMuc' => $dong['ten_danh_muc'],
            ];
        }
        return $danhSachEntities;
    }
}
