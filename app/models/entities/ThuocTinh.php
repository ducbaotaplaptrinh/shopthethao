<?php

namespace app\models\entities;

class ThuocTinh
{
    private ?int $id = null;
    private string $ten_thuoc_tinh = "";
    private  bool $la_bien_the = false;
    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->ten_thuoc_tinh = $data['ten_thuoc_tinh'] ?? "";
        $this->la_bien_the = (bool)$data['la_bien_the'] ?? false;
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
     * Get the value of ten_thuoc_tinh
     */
    public function getTen_thuoc_tinh()
    {
        return $this->ten_thuoc_tinh;
    }

    /**
     * Set the value of ten_thuoc_tinh
     *
     * @return  self
     */
    public function setTen_thuoc_tinh($ten_thuoc_tinh)
    {
        $this->ten_thuoc_tinh = $ten_thuoc_tinh;

        return $this;
    }

    /**
     * Get the value of la_bien_the
     */
    public function getLa_bien_the()
    {
        return $this->la_bien_the;
    }

    /**
     * Set the value of la_bien_the
     *
     * @return  self
     */
    public function setLa_bien_the($la_bien_the)
    {
        $this->la_bien_the = $la_bien_the;

        return $this;
    }
}
