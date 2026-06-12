<?php

namespace app\models;

use app\core\Model;
use app\models\entities\DanhMuc;
use PDO;

class DanhMucModel extends Model
{
    public function getDanhSachDanhMuc(): ?array
    {
        $sql = "SELECT * from danh_muc WHERE ngay_xoa is null and trang_thai = 1 ORDER BY ma_danh_muc_cha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if (!$data) {
            return null;
        }
        $danhsachEntities = [];
        foreach ($data as $dong) {
            $danhsachEntities[] = new DanhMuc($dong);
        }
        return $danhsachEntities;
    }
    public function getDanhMuctheoslug($slug): ?DanhMuc
    {
        $sql = "SELECT * from danh_muc WHERE ngay_xoa is null and duong_dan_slug = :slug";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        return new DanhMuc($data);
    }
}
