<?php

namespace app\models\entities;

use DateTime;

class NguoiDung
{
    private ?int $id = null;
    private ?int $ma_hang = null;
    private string $ho_ten = "";
    private string $email = "";
    private string $mat_khau = "";
    private ?string $so_dien_thoai = null;
    private ?string $anh_dai_dien = null;
    private string $vai_tro = "khach_hang";
    private bool $trang_thai = true;
    private float $tong_chi_tieu = 0.0;
    private ?DateTime $lan_dang_nhap_cuoi = null;
    private ?DateTime $ngay_tao = null;
    private ?DateTime $ngay_cap_nhat = null;
    private ?DateTime $ngay_xoa = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->ma_hang = isset($data['ma_hang']) ? (int)$data['ma_hang'] : null;
            $this->ho_ten = $data['ho_ten'] ?? "";
            $this->email = $data['email'] ?? "";
            $this->mat_khau = $data['mat_khau'] ?? "";
            $this->so_dien_thoai = $data['so_dien_thoai'] ?? null;
            $this->anh_dai_dien = $data['anh_dai_dien'] ?? null;
            $this->vai_tro = $data['vai_tro'] ?? "khach_hang";
            $this->trang_thai = (bool)($data['trang_thai'] ?? true);
            $this->tong_chi_tieu = (float)($data['tong_chi_tieu'] ?? 0.0);
            
            $this->lan_dang_nhap_cuoi = isset($data['lan_dang_nhap_cuoi']) && !empty($data['lan_dang_nhap_cuoi'])
                ? new DateTime($data['lan_dang_nhap_cuoi']) : null;
            $this->ngay_tao = isset($data['ngay_tao']) && !empty($data['ngay_tao'])
                ? new DateTime($data['ngay_tao']) : null;
            $this->ngay_cap_nhat = isset($data['ngay_cap_nhat']) && !empty($data['ngay_cap_nhat'])
                ? new DateTime($data['ngay_cap_nhat']) : null;
            $this->ngay_xoa = isset($data['ngay_xoa']) && !empty($data['ngay_xoa'])
                ? new DateTime($data['ngay_xoa']) : null;
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

    public function getMa_hang(): ?int
    {
        return $this->ma_hang;
    }

    public function setMa_hang(?int $ma_hang): self
    {
        $this->ma_hang = $ma_hang;
        return $this;
    }

    public function getHo_ten(): string
    {
        return $this->ho_ten;
    }

    public function setHo_ten(string $ho_ten): self
    {
        $this->ho_ten = $ho_ten;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getMat_khau(): string
    {
        return $this->mat_khau;
    }

    public function setMat_khau(string $mat_khau): self
    {
        $this->mat_khau = $mat_khau;
        return $this;
    }

    public function getSo_dien_thoai(): ?string
    {
        return $this->so_dien_thoai;
    }

    public function setSo_dien_thoai(?string $so_dien_thoai): self
    {
        $this->so_dien_thoai = $so_dien_thoai;
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

    public function getVai_tro(): string
    {
        return $this->vai_tro;
    }

    public function setVai_tro(string $vai_tro): self
    {
        $this->vai_tro = $vai_tro;
        return $this;
    }

    public function getTrang_thai(): bool
    {
        return $this->trang_thai;
    }

    public function setTrang_thai(bool $trang_thai): self
    {
        $this->trang_thai = $trang_thai;
        return $this;
    }

    public function getTong_chi_tieu(): float
    {
        return $this->tong_chi_tieu;
    }

    public function setTong_chi_tieu(float $tong_chi_tieu): self
    {
        $this->tong_chi_tieu = $tong_chi_tieu;
        return $this;
    }

    public function getLan_dang_nhap_cuoi(): ?DateTime
    {
        return $this->lan_dang_nhap_cuoi;
    }

    public function setLan_dang_nhap_cuoi(?DateTime $lan_dang_nhap_cuoi): self
    {
        $this->lan_dang_nhap_cuoi = $lan_dang_nhap_cuoi;
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

    public function getNgay_cap_nhat(): ?DateTime
    {
        return $this->ngay_cap_nhat;
    }

    public function setNgay_cap_nhat(?DateTime $ngay_cap_nhat): self
    {
        $this->ngay_cap_nhat = $ngay_cap_nhat;
        return $this;
    }

    public function getNgay_xoa(): ?DateTime
    {
        return $this->ngay_xoa;
    }

    public function setNgay_xoa(?DateTime $ngay_xoa): self
    {
        $this->ngay_xoa = $ngay_xoa;
        return $this;
    }
}
