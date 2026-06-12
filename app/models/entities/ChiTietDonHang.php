<?php

namespace app\models\entities;

use DateTime;

class ChiTietDonHang
{
    private ?int $id = null;
    private int $ma_don_hang = 0; // ID of don_hang table
    private int $ma_san_pham = 0;
    private ?int $ma_bien_the = null;
    private string $ten_san_pham = "";
    private ?string $thong_tin_bien_the = null;
    private ?string $anh_dai_dien = null;
    private float $gia_mua = 0.0;
    private int $so_luong = 0;
    private float $thanh_tien = 0.0;
    private ?DateTime $ngay_tao = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->ma_don_hang = (int)($data['ma_don_hang'] ?? 0);
            $this->ma_san_pham = (int)($data['ma_san_pham'] ?? 0);
            $this->ma_bien_the = isset($data['ma_bien_the']) ? (int)$data['ma_bien_the'] : null;
            $this->ten_san_pham = $data['ten_san_pham'] ?? "";
            $this->thong_tin_bien_the = $data['thong_tin_bien_the'] ?? null;
            $this->anh_dai_dien = $data['anh_dai_dien'] ?? null;
            $this->gia_mua = (float)($data['gia_mua'] ?? 0.0);
            $this->so_luong = (int)($data['so_luong'] ?? 0);
            $this->thanh_tien = (float)($data['thanh_tien'] ?? 0.0);
            
            $this->ngay_tao = isset($data['ngay_tao']) && !empty($data['ngay_tao'])
                ? new DateTime($data['ngay_tao']) : null;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getMa_don_hang(): int
    {
        return $this->ma_don_hang;
    }

    public function setMa_don_hang(int $ma_don_hang): self
    {
        $this->ma_don_hang = $ma_don_hang;
        return $this;
    }

    public function getMa_san_pham(): int
    {
        return $this->ma_san_pham;
    }

    public function setMa_san_pham(int $ma_san_pham): self
    {
        $this->ma_san_pham = $ma_san_pham;
        return $this;
    }

    public function getMa_bien_the(): ?int
    {
        return $this->ma_bien_the;
    }

    public function setMa_bien_the(?int $ma_bien_the): self
    {
        $this->ma_bien_the = $ma_bien_the;
        return $this;
    }

    public function getTen_san_pham(): string
    {
        return $this->ten_san_pham;
    }

    public function setTen_san_pham(string $ten_san_pham): self
    {
        $this->ten_san_pham = $ten_san_pham;
        return $this;
    }

    public function getThong_tin_bien_the(): ?string
    {
        return $this->thong_tin_bien_the;
    }

    public function setThong_tin_bien_the(?string $thong_tin_bien_the): self
    {
        $this->thong_tin_bien_the = $thong_tin_bien_the;
        return $this;
    }

    public function getAnh_dai_dien(): ?string
    {
        return $this->anh_dai_dien;
    }

    public function setAnh_dai_dien(?string $anh_dai_dien): self
    {
        $this->anh_dai_dien = $anh_dai_dien;
        return $this;
    }

    public function getGia_mua(): float
    {
        return $this->gia_mua;
    }

    public function setGia_mua(float $gia_mua): self
    {
        $this->gia_mua = $gia_mua;
        return $this;
    }

    public function getSo_luong(): int
    {
        return $this->so_luong;
    }

    public function setSo_luong(int $so_luong): self
    {
        $this->so_luong = $so_luong;
        return $this;
    }

    public function getThanh_tien(): float
    {
        return $this->thanh_tien;
    }

    public function setThanh_tien(float $thanh_tien): self
    {
        $this->thanh_tien = $thanh_tien;
        return $this;
    }

    public function getNgay_tao(): ?DateTime
    {
        return $this->ngay_tao;
    }

    public function setNgay_tao(?DateTime $ngay_tao): self
    {
        $this->ngay_tao = $ngay_tao;
        return $this;
    }
}
