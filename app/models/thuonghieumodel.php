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
}
