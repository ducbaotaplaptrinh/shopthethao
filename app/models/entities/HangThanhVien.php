<?php

namespace app\models\entities;

use DateTime;

class HangThanhVien
{
    private ?int $id = null;
    private string $ten_hang = "";
    private float $muc_chi_tieu_toi_thieu = 0.0;
    private int $phan_tram_giam_gia = 0;
    private ?string $mau_sac = null;
    private ?string $bieu_tuong = null;
    private ?DateTime $ngay_tao = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->ten_hang = $data['ten_hang'] ?? "";
            $this->muc_chi_tieu_toi_thieu = (float)($data['muc_chi_tieu_toi_thieu'] ?? 0.0);
            $this->phan_tram_giam_gia = (int)($data['phan_tram_giam_gia'] ?? 0);
            $this->mau_sac = $data['mau_sac'] ?? null;
            $this->bieu_tuong = $data['bieu_tuong'] ?? null;

            $this->ngay_tao = isset($data['ngay_tao']) && !empty($data['ngay_tao'])
                ? new DateTime($data['ngay_tao']) : null;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTen_hang(): string
    {
        return $this->ten_hang;
    }

    public function getMuc_chi_tieu_toi_thieu(): float
    {
        return $this->muc_chi_tieu_toi_thieu;
    }

    public function getPhan_tram_giam_gia(): int
    {
        return $this->phan_tram_giam_gia;
    }

    public function getMau_sac(): ?string
    {
        return $this->mau_sac;
    }

    public function getBieu_tuong(): ?string
    {
        return $this->bieu_tuong;
    }

    public function getNgay_tao(): ?DateTime
    {
        return $this->ngay_tao;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setTen_hang(string $ten_hang): self
    {
        $this->ten_hang = $ten_hang;
        return $this;
    }

    public function setMuc_chi_tieu_toi_thieu(float $muc_chi_tieu_toi_thieu): self
    {
        $this->muc_chi_tieu_toi_thieu = $muc_chi_tieu_toi_thieu;
        return $this;
    }

    public function setPhan_tram_giam_gia(int $phan_tram_giam_gia): self
    {
        $this->phan_tram_giam_gia = $phan_tram_giam_gia;
        return $this;
    }

    public function setMau_sac(?string $mau_sac): self
    {
        $this->mau_sac = $mau_sac;
        return $this;
    }

    public function setBieu_tuong(?string $bieu_tuong): self
    {
        $this->bieu_tuong = $bieu_tuong;
        return $this;
    }
}
