<?php

namespace app\models\admin;

use app\core\Model;
use PDO;

class AdminAttributeModel extends Model
{
    public function getAllAttributesWithValues()
    {
        // Lấy danh sách nhóm thuộc tính
        $stmt = $this->conn->query("SELECT * FROM thuoc_tinh ORDER BY id DESC");
        $attributes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Lấy danh sách giá trị thuộc tính cho từng nhóm
        foreach ($attributes as &$attr) {
            $stmtVal = $this->conn->prepare("SELECT * FROM gia_tri_thuoc_tinh WHERE ma_thuoc_tinh = ?");
            $stmtVal->execute([$attr['id']]); // Fix prepare without execute bug
            $attr['values'] = $stmtVal->fetchAll(PDO::FETCH_ASSOC);
        }
        return $attributes;
    }
    public function insertAttributeGroup($ten, $labienthe)
    {
        $stmt = $this->conn->prepare("INSERT INTO thuoc_tinh (ten_thuoc_tinh, la_bien_the) VALUES (?, ?)");
        return $stmt->execute([$ten, $labienthe]);
    }
    public function insertAttributeValue($id_thuoc_tinh, $gia_tri)
    {
        $stmt = $this->conn->prepare("INSERT INTO gia_tri_thuoc_tinh (id_thuoc_tinh, gia_tri) VALUES (?, ?)");
        return $stmt->execute([$id_thuoc_tinh, $gia_tri]);
    }
    public function toggleVariantStatus($id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE thuoc_tinh SET la_bien_the = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
