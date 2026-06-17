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
            'email'    => $user->getEmail(),
            'password' => $user->getMat_khau(),
            'phone'    => $user->getSo_dien_thoai(),
            'role'     => $user->getVai_tro(),
            'status'   => $user->getTrang_thai() ? 1 : 0
        ]);

        return (int)$this->conn->lastInsertId();
    }

    public function getUserById(int $id): ?NguoiDung
    {
        $sql = "SELECT * FROM nguoi_dung WHERE id = :id AND ngay_xoa IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new NguoiDung($row) : null;
    }

    public function updateRank(int $userId, float $amountSpent): void
    {
        // Update total spent
        $sql = "UPDATE nguoi_dung SET tong_chi_tieu = tong_chi_tieu + :amount WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['amount' => $amountSpent, 'id' => $userId]);

        // Recalculate rank based on hang_thanh_vien table
        $sqlRank = "UPDATE nguoi_dung 
                    SET ma_hang = (
                        SELECT id 
                        FROM hang_thanh_vien 
                        WHERE muc_chi_tieu_toi_thieu <= nguoi_dung.tong_chi_tieu 
                        ORDER BY muc_chi_tieu_toi_thieu DESC 
                        LIMIT 1
                    ) 
                    WHERE id = :id";
        $stmtRank = $this->conn->prepare($sqlRank);
        $stmtRank->execute(['id' => $userId]);
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $sql = "UPDATE nguoi_dung SET mat_khau = :password, ngay_cap_nhat = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $id
        ]);
    }
}

