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
    public function getSanPhamTheoSlug($slug): ?SanPham
    {
        $sql = "SELECT * FROM SANPHAM WHERE duong_dan_slug = :slug and trang_thai = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $dulieu = $stmt->fetch();
        if (!$dulieu) {
            return null;
        }
        return new SanPham($dulieu);
    }
}
