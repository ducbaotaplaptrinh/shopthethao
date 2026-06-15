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


        $sql = "SELECT s.* FROM bien_the_san_pham b 
            JOIN san_pham s ON s.id = b.ma_san_pham
            JOIN gia_tri_thuoc_tinh_bien_the v ON v.ma_bien_the = b.id
            JOIN gia_tri_thuoc_tinh vt ON vt.id = v.ma_gia_tri_thuoc_tinh
            WHERE vt.gia_tri IN ($inQuery) AND s.trang_thai = 1 AND s.ngay_xoa IS NULL";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($giaTri);
        $data = $stmt->fetchAll();

        if (empty($data)) {
            return null;
        }

        $ListEntities = [];
        foreach ($data as $dong) {
            $ListEntities[] = new SanPham($dong);
        }

        return $ListEntities;
    }

    public function getThuocTinhTheoDm($slugdm): ?array
    {
        $sql = "SELECT DISTINCT
                    tt.ten_thuoc_tinh,
                    gt.id,
                    gt.gia_tri
                FROM thuoc_tinh tt
                JOIN gia_tri_thuoc_tinh gt
                    ON tt.id = gt.ma_thuoc_tinh
                JOIN gia_tri_thuoc_tinh_bien_the gtttbt
                    ON gtttbt.ma_gia_tri_thuoc_tinh = gt.id
                JOIN bien_the_san_pham bt
                    ON bt.id = gtttbt.ma_bien_the
                JOIN san_pham s
                    ON s.id = bt.ma_san_pham
                JOIN danh_muc dm
                    ON dm.id = s.ma_danh_muc
                WHERE dm.duong_dan_slug = :slug

                ORDER BY ten_thuoc_tinh, gia_tri;";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slugdm]);
        $data = $stmt->fetchAll();
        if (empty($data)) {
            return null;
        }
        $ListEntities = [];
        foreach ($data as $dong) {
            $ListEntities[] = [
                'tenThuocTinh' => $dong['ten_thuoc_tinh'],
                'giaTri' => $dong['gia_tri'],
                'id' => $dong['id'],
            ];
        }


        return $ListEntities;
    }
}
