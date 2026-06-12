<?php

namespace app\models;

use app\core\Model;
use app\models\entities\NguoiDung;
use PDO;

class NguoiDungModel extends Model
{
    public function getUserByEmail(string $email): ?NguoiDung
    {
        $sql = "SELECT * FROM nguoi_dung WHERE email = :email AND ngay_xoa IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? new NguoiDung($row) : null;
    }

    public function getUserByPhone(string $phone): ?NguoiDung
    {
        $sql = "SELECT * FROM nguoi_dung WHERE so_dien_thoai = :phone AND ngay_xoa IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['phone' => $phone]);
        $row = $stmt->fetch();
        return $row ? new NguoiDung($row) : null;
    }

    public function createUser(NguoiDung $user): int
    {
        $sql = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, vai_tro, trang_thai) 
                VALUES (:fullname, :email, :password, :phone, :role, :status)";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'fullname' => $user->getHo_ten(),
            'email' => $user->getEmail(),
            'password' => $user->getMat_khau(), 
            'phone' => $user->getSo_dien_thoai(),
            'role' => $user->getVai_tro(),
            'status' => $user->getTrang_thai() ? 1 : 0
        ]);

        return (int)$this->conn->lastInsertId();
    }
}
