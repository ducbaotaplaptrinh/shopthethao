<?php

namespace app\models;

use app\core\Model;
use app\models\entities\ThuongHieu;
use PDO;

class ThuongHieuModel extends Model
{
    public function getDanhSachThuongHieu(): ?array
    {
        $sql = "SELECT * from thuong_hieu WHERE ngay_xoa is null";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if (!$data) {
            return null;
        }
        $danhsachEntities = [];
        foreach ($data as $dong) {
            $danhsachEntities[] = new ThuongHieu($dong);
        }
        return $danhsachEntities;
    }
    public function getTHTheoDM($slug): ?array
    {
        $sql = "SELECT DISTINCT t.id, t.ten_thuong_hieu, t.duong_dan_slug, t.anh_logo 
                from thuong_hieu t
                join san_pham s on s.ma_thuong_hieu = t.id
                join danh_muc d on d.id = s.ma_danh_muc
                WHERE t.ngay_xoa is null and d.duong_dan_slug = :slug";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $data = $stmt->fetchAll();
        if (!$data) {
            return null;
        }
        $danhsachEntities = [];
        foreach ($data as $dong) {
            $danhsachEntities[] = new ThuongHieu($dong);
        }
        return $danhsachEntities;
    }
    public function getThuongHieutheoslug($slug): ?ThuongHieu
    {
        $sql = "SELECT * from thuong_hieu WHERE ngay_xoa is null and duong_dan_slug = :slug";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        return new ThuongHieu($data);
    }
}
