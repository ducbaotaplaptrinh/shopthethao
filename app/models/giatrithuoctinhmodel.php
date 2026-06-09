<?php

namespace app\models;

use app\core\Model;
use app\models\entities\SanPham;
use PDO;

class GiaTriThuocTinhModel extends Model
{
    public function getSanPhamTheoGiaTriTT($giaTri = []): ?array
    {

        if (empty($giaTri)) {
            return null;
        }

        // Tạo chuỗi dấu hỏi tương ứng với độ dài mảng $giaTri (VD: "?, ?, ?")
        //implode chuyển mảng thành chuỗi
        $inQuery = implode(',', array_fill(0, count($giaTri), '?'));


        $sql = "SELECT vt.gia_tri AS giatrithuoctinh, s.* FROM bien_the_san_pham b 
            JOIN san_pham s ON s.id = b.ma_san_pham
            JOIN gia_tri_thuoc_tinh_bien_the v ON v.ma_bien_the = b.id
            JOIN gia_tri_thuoc_tinh vt ON vt.id = v.ma_gia_tri_thuoc_tinh
            WHERE vt.gia_tri IN ($inQuery) AND s.trang_thai = 1 AND s.ngay_xoa IS NULL";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($giaTri);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($data)) {
            return null;
        }

        $ListEntities = [];
        foreach ($data as $dong) {
            $ListEntities[] = new SanPham($dong);
        }

        return $ListEntities;
    }
}
