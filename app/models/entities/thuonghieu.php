<?php

namespace app\models\entities;

use DateTime;
use Exception;

class ThuongHieu
{
    private ?int $id = null;
    private string $ten_thuong_hieu = "";
    private string $duong_dan_slug = "";
    private ?string $anh_logo = null;
    private ?string $mo_ta = null;
    private bool $trang_thai = true;
    private ?DateTime $ngay_cap_nhat = null;
    private ?DateTime $ngay_tao = null;
    private ?DateTime $ngay_xoa = null;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->ten_thuong_hieu = $data['ten_thuong_hieu'] ?? "";
        $this->duong_dan_slug = $data['duong_dan_slug'] ?? "";
        $this->anh_logo = $data['anh_logo'] ?? null;
        $this->mo_ta = $data['mo_ta'] ?? null;
        $this->trang_thai = (bool)($data['trang_thai'] ?? true);
        $this->ngay_cap_nhat = isset($data['ngay_cap_nhat']) ? new DateTime($data['ngay_cap_nhat']) : null;
        $this->ngay_tao = isset($data['ngay_tao']) ? new DateTime($data['ngay_tao']) : null;
        $this->ngay_xoa = isset($data['ngay_xoa']) ? new DateTime($data['ngay_xoa']) : null;
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


    public function getTen_thuong_hieu()
    {
        return $this->ten_thuong_hieu;
    }
    public function setTen_thuong_hieu($ten_thuong_hieu)
    {
        $this->ten_thuong_hieu = $ten_thuong_hieu;

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


    public function getAnh_logo()
    {
        return $this->anh_logo;
    }
    public function setAnh_logo($anh_logo)
    {
        $this->anh_logo = $anh_logo;

        return $this;
    }


    public function getMo_ta()
    {
        return $this->mo_ta;
    }
    public function setMo_ta($mo_ta)
    {
        $this->mo_ta = $mo_ta;

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


    public function getNgay_xoa()
    {
        return $this->ngay_xoa;
    }
    public function setNgay_xoa($ngay_xoa)
    {
        $this->ngay_xoa = $ngay_xoa;

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
}
