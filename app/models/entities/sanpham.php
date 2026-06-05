<?php

namespace app\models\entities;

use DateTime;
use Exception;

class SanPham
{
    private ?int $id = null;
    private int $ma_danh_muc = 0;
    private int $ma_thuong_tieu = 0;
    private string $ten_san_pham = "";
    private string $duong_dan_slug = "";
    private ?string $anh_dai_dien = null;
    private ?string $mo_ta_ngan = null;
    private ?string $mo_ta_chi_tiet = null;
    private ?string $ma_vach_sku = null;
    private float $gia_ban =  0.0;
    private ?float $gia_khuyen_mai = 0.0;
    private int $so_luong_ton = 0;
    private float $trong_luong = 0.0;
    private int $luot_xem = 0;
    private bool $la_noi_bat = false;
    private bool $trang_thai = true;
    private ?DateTime $ngay_tao;
    private ?DateTime $ngay_cap_nhat;
    private ?DateTime $ngay_xoa;

    public function __construct(array $dulieu = [])
    {
        if (!empty($dulieu)) {
            $this->id = $dulieu['id'] ?? null;
            $this->ma_danh_muc = $dulieu['ma_danh_muc'];
            $this->ma_thuong_tieu = $dulieu['ma_thuong_tieu'];
            $this->ten_san_pham = $dulieu['ten_san_pham'];
            $this->duong_dan_slug = $dulieu['duong_dan_slug'] ?? null;
            $this->anh_dai_dien = $dulieu['anh_dai_dien'] ?? null;
            $this->mo_ta_ngan = $dulieu['mo_ta_ngan'] ?? null;
            $this->mo_ta_chi_tiet = $dulieu['mo_ta_chi_tiet'] ?? null;
            $this->ma_vach_sku = $dulieu['ma_vach_sku'] ?? null;
            $this->gia_ban = (float)($dulieu['gia_ban'] ?? 0);
            $this->gia_khuyen_mai = (float)($dulieu['gia_khuyen_mai'] ?? 0);
            $this->so_luong_ton = (int)($dulieu['so_luong_ton'] ?? 0);
            $this->trong_luong = (int)($dulieu['trong_luong'] ?? 0);
            $this->luot_xem = (int)($dulieu['luot_xem'] ?? 0);
            $this->la_noi_bat = (bool)($dulieu['la_noi_bat'] ?? false);
            $this->trang_thai = (bool)($dulieu['trang_thai'] ?? false);
            $this->ngay_tao = isset($dulieu['ngay_tao']) ? new DateTime($dulieu['ngay_tao']) : null;
            $this->ngay_cap_nhat = isset($dulieu['ngay_cap_nhat']) ? new DateTime($dulieu['ngay_cap_nhat']) : null;
            $this->ngay_xoa = isset($dulieu['ngay_xoa']) ? new DateTime($dulieu['ngay_xoa']) : null;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    public function getMa_danh_muc()
    {
        return $this->ma_danh_muc;
    }

    public function setMa_danh_muc($ma_danh_muc)
    {
        $this->ma_danh_muc = $ma_danh_muc;

        return $this;
    }
    public function getMa_thuong_tieu()
    {
        return $this->ma_thuong_tieu;
    }

    public function setMa_thuong_tieu($ma_thuong_tieu)
    {
        $this->ma_thuong_tieu = $ma_thuong_tieu;

        return $this;
    }
    public function getTen_san_pham()
    {
        return $this->ten_san_pham;
    }

    public function setTen_san_pham($ten_san_pham)
    {
        $this->ten_san_pham = $ten_san_pham;

        return $this;
    }
    public function getDuong_dan_slug()
    {
        return $this->duong_dan_slug;
    }

    public function setDuong_dan_slug($duong_dan_slug)
    {
        $this->duong_dan_slug = $duong_dan_slug;

        return $this;
    }
    public function getAnh_dai_dien()
    {
        return $this->anh_dai_dien;
    }

    public function setAnh_dai_dien($anh_dai_dien)
    {
        $this->anh_dai_dien = $anh_dai_dien;

        return $this;
    }
    public function getMo_na_ngan()
    {
        return $this->mo_ta_ngan;
    }

    public function setMo_na_ngan($mo_ta_ngan)
    {
        $this->mo_ta_ngan = $mo_ta_ngan;

        return $this;
    }
    public function getMo_ta_chi_tiet()
    {
        return $this->mo_ta_chi_tiet;
    }

    public function setMo_ta_chi_tiet($mo_ta_chi_tiet)
    {
        $this->mo_ta_chi_tiet = $mo_ta_chi_tiet;

        return $this;
    }
    public function getMa_vach_sku()
    {
        return $this->ma_vach_sku;
    }

    public function setMa_vach_sku($ma_vach_sku)
    {
        $this->ma_vach_sku = $ma_vach_sku;

        return $this;
    }
    public function getGia_ban()
    {
        return $this->gia_ban;
    }

    public function setGia_ban($gia_ban)
    {
        $this->gia_ban = $gia_ban;

        return $this;
    }
    public function getGia_khuyen_mai()
    {
        return $this->gia_khuyen_mai;
    }

    public function setGia_khuyen_mai($gia_khuyen_mai)
    {
        if ($gia_khuyen_mai >= $this->gia_ban) {
            throw new Exception("Giá khuyến mãi phải nhỏ hơn giá bán!");
        } else {
            $this->gia_khuyen_mai = $gia_khuyen_mai;
        }
        return $this;
    }
    public function getSo_luong_ton()
    {
        return $this->so_luong_ton;
    }

    public function setSo_luong_ton($so_luong_ton)
    {
        $this->so_luong_ton = $so_luong_ton;

        return $this;
    }
    public function getTrong_luong()
    {
        return $this->trong_luong;
    }

    public function setTrong_luong($trong_luong)
    {
        $this->trong_luong = $trong_luong;

        return $this;
    }
    public function getLuot_xem()
    {
        return $this->luot_xem;
    }

    public function setLuot_xem($luot_xem)
    {
        $this->luot_xem = $luot_xem;

        return $this;
    }
    public function getLa_noi_bat()
    {
        return $this->la_noi_bat;
    }

    public function setLa_noi_bat($la_noi_bat)
    {
        $this->la_noi_bat = $la_noi_bat;

        return $this;
    }
    public function getTrang_thai()
    {
        return $this->trang_thai;
    }

    public function setTrang_thai($trang_thai)
    {
        $this->trang_thai = $trang_thai;

        return $this;
    }
    public function getNgay_tao()
    {
        return $this->ngay_tao;
    }

    public function setNgay_tao($ngay_tao)
    {
        $this->ngay_tao = $ngay_tao;

        return $this;
    }
    public function getNgay_cap_nhat()
    {
        return $this->ngay_cap_nhat;
    }

    public function setNgay_cap_nhat($ngay_cap_nhat)
    {
        $this->ngay_cap_nhat = $ngay_cap_nhat;

        return $this;
    }
    public function getNgay_xoa()
    {
        return $this->ngay_xoa;
    }

    public function setNgay_xoa($ngay_xoa)
    {
        $this->ngay_xoa = $ngay_xoa;

        return $this;
    }

    // Phuương thức xử lý
    public function getPhanTramGiam(): int
    {
        if (!empty($this->gia_khuyen_mai && $this->gia_ban > 0)) {
            return (int)round((($this->gia_ban - $this->gia_khuyen_mai) / $this->gia_ban * 100));
        }
        return 0;
    }
}
