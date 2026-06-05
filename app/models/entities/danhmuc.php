<?php

namespace app\models\entities;

use DateTime;
use Exception;

class DanhMuc
{
    private ?int $id = null;
    private int $ma_danh_muc_cha = 0;
    private string $ten_danh_muc = "";
    private ?string $hinh_anh = null;
    private ?string $mo_ta = null;
    private int $thu_tu_sap_xep = 0;
    private bool $trang_thai = true;
    private ?DateTime $ngay_tao;
    private ?DateTime $ngay_cap_nhat;
    private ?DateTime $ngay_xoa;
    public function __construct(array $data = [])
    {
        if (!empty($data)) {

            $this->id = $data['id'] ?? null;
            $this->ma_danh_muc_cha = isset($data['ma_danh_muc_cha']) ? (int)$data['ma_danh_muc_cha'] : null;
            $this->ten_danh_muc = $data['ten_danh_muc'];
            $this->hinh_anh = $data['hinh_anh'] ?? null;
            $this->mo_ta = $data['mo_ta'] ?? null;
            $this->thu_tu_sap_xep = (int)($data['thu_tu_sap_xep'] ?? 0);
            $this->trang_thai = (bool)($data['trang_thai'] ?? true);
            $this->ngay_tao = isset($data['ngay_tao']) ? new DateTime($data['ngay_tao']) : null;
            $this->ngay_cap_nhat = isset($data['ngay_cap_nhat']) ? new DateTime($data['ngay_cap_nhat']) : null;
            $this->ngay_xoa = isset($data['ngay_xoa']) ? new DateTime($data['ngay_xoa']) : null;
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


    public function getMa_danh_muc_cha()
    {
        return $this->ma_danh_muc_cha;
    }

    public function setMa_danh_muc_cha($ma_danh_muc_cha)
    {
        $this->ma_danh_muc_cha = $ma_danh_muc_cha;

        return $this;
    }


    public function getTen_danh_muc()
    {
        return $this->ten_danh_muc;
    }

    public function setTen_danh_muc($ten_danh_muc)
    {
        $this->ten_danh_muc = $ten_danh_muc;

        return $this;
    }


    public function getHinh_anh()
    {
        return $this->hinh_anh;
    }

    public function setHinh_anh($hinh_anh)
    {
        $this->hinh_anh = $hinh_anh;

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


    public function getThu_tu_sap_xep()
    {
        return $this->thu_tu_sap_xep;
    }

    public function setThu_tu_sap_xep($thu_tu_sap_xep)
    {
        $this->thu_tu_sap_xep = $thu_tu_sap_xep;

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
}
