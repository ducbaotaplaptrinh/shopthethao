<?php

namespace app\models\entities;

class GiaTriThuocTinh
{
    private ?int $id = null;
    private int $ma_thuoc_tinh = 0;
    private string $gia_tri = "";
    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->ma_thuoc_tinh = $data['ma_thuoc_tinh'] ?? 0;
        $this->gia_tri = $data['gia_tri'] ?? "";
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
     * Get the value of ma_thuoc_tinh
     */
    public function getMa_thuoc_tinh()
    {
        return $this->ma_thuoc_tinh;
    }

    /**
     * Set the value of ma_thuoc_tinh
     *
     * @return  self
     */
    public function setMa_thuoc_tinh($ma_thuoc_tinh)
    {
        $this->ma_thuoc_tinh = $ma_thuoc_tinh;

        return $this;
    }

    /**
     * Get the value of gia_tri
     */
    public function getGia_tri()
    {
        return $this->gia_tri;
    }

    /**
     * Set the value of gia_tri
     *
     * @return  self
     */
    public function setGia_tri($gia_tri)
    {
        $this->gia_tri = $gia_tri;

        return $this;
    }
}
