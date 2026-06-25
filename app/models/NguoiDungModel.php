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

    public function assignDefaultRank(int $userId): ?array
    {
        $sqlDefaultRank = "SELECT id, ten_hang, mau_sac, bieu_tuong FROM hang_thanh_vien ORDER BY muc_chi_tieu_toi_thieu ASC LIMIT 1";
        $stmtDefaultRank = $this->conn->query($sqlDefaultRank);
        $defaultRank = $stmtDefaultRank->fetch(PDO::FETCH_ASSOC);

        if ($defaultRank) {
            $sqlUpdateUserRank = "UPDATE nguoi_dung SET ma_hang = :ma_hang WHERE id = :uid";
            $stmtUpdateUserRank = $this->conn->prepare($sqlUpdateUserRank);
            $stmtUpdateUserRank->execute(['ma_hang' => $defaultRank['id'], 'uid' => $userId]);
        }
        return $defaultRank ?: null;
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

    public function updateUser(NguoiDung $user, bool $updatePassword = false): bool
    {
        if ($updatePassword) {
            $sql = "UPDATE nguoi_dung 
                    SET ho_ten = :fullname, email = :email, so_dien_thoai = :phone, 
                        anh_dai_dien = :avatar, mat_khau = :password, ngay_cap_nhat = NOW() 
                    WHERE id = :id";
            $params = [
                'fullname' => $user->getHo_ten(),
                'email'    => $user->getEmail(),
                'phone'    => $user->getSo_dien_thoai(),
                'avatar'   => $user->getAnh_dai_dien(),
                'password' => $user->getMat_khau(),
                'id'       => $user->getId()
            ];
        } else {
            $sql = "UPDATE nguoi_dung 
                    SET ho_ten = :fullname, email = :email, so_dien_thoai = :phone, 
                        anh_dai_dien = :avatar, ngay_cap_nhat = NOW() 
                    WHERE id = :id";
            $params = [
                'fullname' => $user->getHo_ten(),
                'email'    => $user->getEmail(),
                'phone'    => $user->getSo_dien_thoai(),
                'avatar'   => $user->getAnh_dai_dien(),
                'id'       => $user->getId()
            ];
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function getUserAddresses(int $userId): array
    {
        $sql = "SELECT * FROM dia_chi_nguoi_dung WHERE ma_nguoi_dung = :uid ORDER BY la_mac_dinh DESC, ngay_tao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll() ?: [];
    }

    public function addAddress(array $data): bool
    {
        // If this is set to default, unset others first
        if (!empty($data['la_mac_dinh']) && $data['la_mac_dinh'] == 1) {
            $sqlUnset = "UPDATE dia_chi_nguoi_dung SET la_mac_dinh = 0 WHERE ma_nguoi_dung = :uid";
            $stmtUnset = $this->conn->prepare($sqlUnset);
            $stmtUnset->execute(['uid' => $data['ma_nguoi_dung']]);
        } else {
            // If it's the first address, make it default automatically
            $sqlCheck = "SELECT COUNT(*) FROM dia_chi_nguoi_dung WHERE ma_nguoi_dung = :uid";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute(['uid' => $data['ma_nguoi_dung']]);
            $count = (int)$stmtCheck->fetchColumn();
            if ($count === 0) {
                $data['la_mac_dinh'] = 1;
            }
        }

        $sql = "INSERT INTO dia_chi_nguoi_dung (ma_nguoi_dung, ho_ten_nguoi_nhan, so_dien_thoai, dia_chi_chi_tiet, phuong_xa, quan_huyen, tinh_thanh_pho, la_mac_dinh)
                VALUES (:uid, :recipient_name, :phone, :detail, :ward, :district, :province, :is_default)";
        $stmt = $this->conn->prepare($sql);
        $sqlCheck = "SELECT id FROM nguoi_dung WHERE id = ?";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([$data['ma_nguoi_dung']]);

        // echo '<pre>';
        // var_dump($data['ma_nguoi_dung']);
        // var_dump($stmtCheck->fetch(PDO::FETCH_ASSOC));
        // die();
        return $stmt->execute([
            'uid' => $data['ma_nguoi_dung'],
            'recipient_name' => $data['ho_ten_nguoi_nhan'],
            'phone' => $data['so_dien_thoai'],
            'detail' => $data['dia_chi_chi_tiet'],
            'ward' => $data['phuong_xa'],
            'district' => $data['quan_huyen'],
            'province' => $data['tinh_thanh_pho'],
            'is_default' => !empty($data['la_mac_dinh']) ? 1 : 0
        ]);
    }

    public function updateAddress(int $addressId, int $userId, array $data): bool
    {
        // If this is set to default, unset others first
        if (!empty($data['la_mac_dinh']) && $data['la_mac_dinh'] == 1) {
            $sqlUnset = "UPDATE dia_chi_nguoi_dung SET la_mac_dinh = 0 WHERE ma_nguoi_dung = :uid";
            $stmtUnset = $this->conn->prepare($sqlUnset);
            $stmtUnset->execute(['uid' => $userId]);
        }

        $sql = "UPDATE dia_chi_nguoi_dung 
                SET ho_ten_nguoi_nhan = :recipient_name, so_dien_thoai = :phone, 
                    dia_chi_chi_tiet = :detail, phuong_xa = :ward, 
                    quan_huyen = :district, tinh_thanh_pho = :province, la_mac_dinh = :is_default
                WHERE id = :id AND ma_nguoi_dung = :uid";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $addressId,
            'uid' => $userId,
            'recipient_name' => $data['ho_ten_nguoi_nhan'],
            'phone' => $data['so_dien_thoai'],
            'detail' => $data['dia_chi_chi_tiet'],
            'ward' => $data['phuong_xa'],
            'district' => $data['quan_huyen'],
            'province' => $data['tinh_thanh_pho'],
            'is_default' => !empty($data['la_mac_dinh']) ? 1 : 0
        ]);
    }

    public function deleteAddress(int $addressId, int $userId): bool
    {
        // Check if the address to delete is default
        $sqlCheck = "SELECT la_mac_dinh FROM dia_chi_nguoi_dung WHERE id = :id AND ma_nguoi_dung = :uid";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute(['id' => $addressId, 'uid' => $userId]);
        $isDefault = (int)$stmtCheck->fetchColumn();

        // Delete address
        $sql = "DELETE FROM dia_chi_nguoi_dung WHERE id = :id AND ma_nguoi_dung = :uid";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute(['id' => $addressId, 'uid' => $userId]);

        // If we deleted the default address, make another one default
        if ($result && $isDefault === 1) {
            $sqlNext = "SELECT id FROM dia_chi_nguoi_dung WHERE ma_nguoi_dung = :uid LIMIT 1";
            $stmtNext = $this->conn->prepare($sqlNext);
            $stmtNext->execute(['uid' => $userId]);
            $nextId = $stmtNext->fetchColumn();
            if ($nextId) {
                $sqlSetDefault = "UPDATE dia_chi_nguoi_dung SET la_mac_dinh = 1 WHERE id = :id";
                $stmtSetDefault = $this->conn->prepare($sqlSetDefault);
                $stmtSetDefault->execute(['id' => $nextId]);
            }
        }

        return $result;
    }

    public function setDefaultAddress(int $addressId, int $userId): bool
    {
        // Unset current default
        $sqlUnset = "UPDATE dia_chi_nguoi_dung SET la_mac_dinh = 0 WHERE ma_nguoi_dung = :uid";
        $stmtUnset = $this->conn->prepare($sqlUnset);
        $stmtUnset->execute(['uid' => $userId]);

        // Set new default
        $sql = "UPDATE dia_chi_nguoi_dung SET la_mac_dinh = 1 WHERE id = :id AND ma_nguoi_dung = :uid";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $addressId, 'uid' => $userId]);
    }
}
