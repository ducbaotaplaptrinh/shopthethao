<?php

namespace app\models;

use app\core\Model;
use PDO;

class BannerModel extends Model
{
    public function getAllBanners(): array
    {
        $sql = "SELECT * FROM anh_quang_cao ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getActiveBanners(string $position = 'slide_chinh'): array
    {
        $sql = "SELECT * FROM anh_quang_cao WHERE trang_thai = 1 AND vi_tri_hien_thi = :position ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['position' => $position]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getBannerById(int $id): ?array
    {
        $sql = "SELECT * FROM anh_quang_cao WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function storeBanner(array $data): bool
    {
        $sql = "INSERT INTO anh_quang_cao (tieu_de, duong_dan_anh, duong_dan_lien_ket, vi_tri_hien_thi, trang_thai) 
                VALUES (:tieu_de, :duong_dan_anh, :duong_dan_lien_ket, :vi_tri_hien_thi, :trang_thai)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'tieu_de' => $data['tieu_de'] ?? null,
            'duong_dan_anh' => $data['duong_dan_anh'],
            'duong_dan_lien_ket' => $data['duong_dan_lien_ket'] ?? null,
            'vi_tri_hien_thi' => $data['vi_tri_hien_thi'] ?? 'slide_chinh',
            'trang_thai' => $data['trang_thai'] ?? 1
        ]);
    }

    public function updateBanner(int $id, array $data): bool
    {
        $sql = "UPDATE anh_quang_cao 
                SET tieu_de = :tieu_de, 
                    duong_dan_anh = :duong_dan_anh, 
                    duong_dan_lien_ket = :duong_dan_lien_ket, 
                    vi_tri_hien_thi = :vi_tri_hien_thi, 
                    trang_thai = :trang_thai 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'tieu_de' => $data['tieu_de'] ?? null,
            'duong_dan_anh' => $data['duong_dan_anh'],
            'duong_dan_lien_ket' => $data['duong_dan_lien_ket'] ?? null,
            'vi_tri_hien_thi' => $data['vi_tri_hien_thi'] ?? 'slide_chinh',
            'trang_thai' => $data['trang_thai'] ?? 1
        ]);
    }

    public function deleteBanner(int $id): bool
    {
        $sql = "DELETE FROM anh_quang_cao WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
