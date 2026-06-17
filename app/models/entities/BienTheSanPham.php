<?php

namespace app\models\entities;

use DateTime;

class GiaTriThuocTinh
{
    private ?int $id = null;
    private int $ma_san_pham = 0;
    private ?string $ma_vach_sku = "";
    private ?int $gia_ban_rieng = null;
    private ?int $so_luong_ton = null;
    private ?string $anh_rieng = null;
    private ?DateTime $ngay_tao = null;
    private ?DateTime $ngay_xao = null;
    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->ma_san_pham = $data['ma_san_pham'] ?? 0;
        $this->ma_vach_sku = $data['ma_vach_sku'] ?? null;
        $this->gia_ban_rieng = $data['gia_ban_rieng'] ?? null;
        $this->so_luong_ton = $data['so_luong_ton'] ?? null;
        $this->anh_rieng = $data['anh_rieng'] ?? null;
        $this->ngay_tao = (isset($data['ngay_tao'])) ? new DateTime($data['ngay_tao']) : null;
        $this->ngay_xao = (isset($data['ngay_xao'])) ? new DateTime($data['ngay_xao']) : null;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of ma_san_pham
     */
    public function getMa_san_pham()
    {
        return $this->ma_san_pham;
    }

    /**
     * Set the value of ma_san_pham
     *
     * @return  self
     */
    public function setMa_san_pham($ma_san_pham)
    {
        $this->ma_san_pham = $ma_san_pham;

        return $this;
    }

    /**
     * Get the value of ma_vach_sku
     */
    public function getMa_vach_sku()
    {
        return $this->ma_vach_sku;
    }

    /**
     * Set the value of ma_vach_sku
     *
     * @return  self
     */
    public function setMa_vach_sku($ma_vach_sku)
    {
        $this->ma_vach_sku = $ma_vach_sku;

        return $this;
    }

    /**
     * Get the value of gia_ban_rieng
     */
    public function getGia_ban_rieng()
    {
        return $this->gia_ban_rieng;
    }

    /**
     * Set the value of gia_ban_rieng
     *
     * @return  self
     */
    public function setGia_ban_rieng($gia_ban_rieng)
    {
        $this->gia_ban_rieng = $gia_ban_rieng;

        return $this;
    }

    /**
     * Get the value of so_luong_ton
     */
    public function getSo_luong_ton()
    {
        return $this->so_luong_ton;
    }

    /**
     * Set the value of so_luong_ton
     *
     * @return  self
     */
    public function setSo_luong_ton($so_luong_ton)
    {
        $this->so_luong_ton = $so_luong_ton;

        return $this;
    }

    /**
     * Get the value of anh_rieng
     */
    public function getAnh_rieng()
    {
        return $this->anh_rieng;
    }

    /**
     * Set the value of anh_rieng
     *
     * @return  self
     */
    public function setAnh_rieng($anh_rieng)
    {
        $this->anh_rieng = $anh_rieng;

        return $this;
    }

    /**
     * Get the value of ngay_tao
     */
    public function getNgay_tao()
    {
        return $this->ngay_tao;
    }

    /**
     * Set the value of ngay_tao
     *
     * @return  self
     */
    public function setNgay_tao($ngay_tao)
    {
        $this->ngay_tao = $ngay_tao;

        return $this;
    }

    /**
     * Get the value of ngay_xao
     */
    public function getNgay_xao()
    {
        return $this->ngay_xao;
    }

    /**
     * Set the value of ngay_xao
     *
     * @return  self
     */
    public function setNgay_xao($ngay_xao)
    {
        $this->ngay_xao = $ngay_xao;

        return $this;
    }
}
