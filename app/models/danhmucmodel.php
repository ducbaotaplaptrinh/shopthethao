<?php

namespace app\models;

use app\core\Model;
use app\models\entities\DanhMuc;
use PDO;

class DanhMucModel extends Model
{
    public function getDanhSachDanhMuc(): ?array
    {
        $sql = "SELECT * from danh_muc WHERE ngay_xoa is null ORDER BY thu_tu_sap_xep,ten_danh_muc ASC";
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
}
