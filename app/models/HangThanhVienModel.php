<?php

namespace app\models;

use app\core\Model;
use app\models\entities\HangThanhVien;
use PDO;

class HangThanhVienModel extends Model
{
    public function getHangThanhVien($uid): ?array
    {
        $sql = "SELECT ht.ten_hang, ht.mau_sac, ht.bieu_tuong 
                        FROM hang_thanh_vien ht 
                        WHERE ht.id = (SELECT ma_hang FROM nguoi_dung WHERE id = :uid)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uid' => $uid]);
        $data = $stmt->fetch();
        if (empty($data)) {
            return null;
        }
        return $data;
    }
}
