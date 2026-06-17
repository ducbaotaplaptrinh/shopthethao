<?php

namespace app\models\admin;

use app\core\Model;
use PDO;

class AdminCustomerModel extends Model
{
    public function getAllCustomers()
    {
        $sql = "SELECT nd.*, ht.ten_hang, ht.mau_sac, ht.bieu_tuong,
                (SELECT COUNT(*) FROM don_hang WHERE ma_nguoi_dung = nd.id) as so_don_hang
                FROM nguoi_dung nd
                LEFT JOIN hang_thanh_vien ht ON nd.ma_hang = ht.id
                WHERE nd.vai_tro = 'khach_hang'
                ORDER BY nd.tong_chi_tieu DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTiers()
    {
        return $this->conn->query("SELECT * FROM hang_thanh_vien ORDER BY muc_chi_tieu_toi_thieu ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleCustomerStatus($id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE nguoi_dung SET trang_thai = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
