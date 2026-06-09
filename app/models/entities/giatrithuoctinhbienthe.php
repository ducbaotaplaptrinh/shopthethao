<?php

namespace app\models\entities;

class GiaTriThuocTinhBienThe
{
    private int $ma_bien_the = 0;
    private int $ma_thuoc_tinh = 0;

    public function __construct($data = [])
    {
        $this->ma_bien_the = $data['ma_bien_the'] ?? 0;
        $this->ma_thuoc_tinh = $data['ma_thuoc_tinh'] ?? 0;
    }



    /**
     * Get the value of ma_bien_the
     */
    public function getMa_bien_the()
    {
        return $this->ma_bien_the;
    }

    /**
     * Set the value of ma_bien_the
     *
     * @return  self
     */
    public function setMa_bien_the($ma_bien_the)
    {
        $this->ma_bien_the = $ma_bien_the;

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
}
