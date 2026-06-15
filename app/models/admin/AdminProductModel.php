<?php

namespace app\models\admin;

use app\core\Model;
use PDO;

class AdminProductModel extends Model
{
    public function getAllProducts()
    {
        $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu,
                (SELECT SUM(so_luong_ton) FROM bien_the_san_pham WHERE ma_san_pham = sp.id) as tong_ton_kho,
                (SELECT COUNT(*) FROM bien_the_san_pham WHERE ma_san_pham = sp.id AND so_luong_ton < 5) as so_bien_the_het_hang
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.id
                LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.id
                ORDER BY sp.id DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriesForDropdown()
    {
        return $this->conn->query("SELECT id, ten_danh_muc FROM danh_muc")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBrandsForDropdown()
    {
        return $this->conn->query("SELECT id, ten_thuong_hieu FROM thuong_hieu")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVariantAttributes()
    {
        $attributes = [];
        $stmtAttr = $this->conn->query("SELECT * FROM thuoc_tinh WHERE la_bien_the = 1");
        while ($attr = $stmtAttr->fetch(PDO::FETCH_ASSOC)) {
            $stmtVal = $this->conn->prepare("SELECT * FROM gia_tri_thuoc_tinh WHERE id_thuoc_tinh = ?");
            $stmtVal->execute([$attr['id']]);
            $attr['values'] = $stmtVal->fetchAll(PDO::FETCH_ASSOC);
            $attributes[] = $attr;
        }
        return $attributes;
    }

    public function insertProductWithVariants($data, $variantsJson)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Insert Base Product
            $sqlProd = "INSERT INTO san_pham (ten_san_pham, duong_dan, id_danh_muc, id_thuong_hieu, mo_ta, gia_goc, la_noi_bat, trang_thai) 
                        VALUES (:ten, :slug, :dm, :th, :mota, :gia, :noibat, :trangthai)";

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['ten_san_pham'])));

            $stmtProd = $this->conn->prepare($sqlProd);
            $stmtProd->execute([
                'ten' => $data['ten_san_pham'],
                'slug' => $slug,
                'dm' => $data['id_danh_muc'],
                'th' => $data['id_thuong_hieu'] ?: null,
                'mota' => $data['mo_ta'] ?? '',
                'gia' => $data['gia_goc'] ?? 0,
                'noibat' => isset($data['la_noi_bat']) ? 1 : 0,
                'trangthai' => isset($data['trang_thai']) ? 1 : 0
            ]);

            $productId = $this->conn->lastInsertId();

            // 2. Insert Variants
            if (!empty($variantsJson)) {
                $variants = json_decode($variantsJson, true);
                foreach ($variants as $variant) {
                    $sqlVar = "INSERT INTO bien_the_san_pham (id_san_pham, ma_vach_sku, gia_ban, so_luong_ton) 
                               VALUES (:id_sp, :sku, :gia, :sl)";
                    $stmtVar = $this->conn->prepare($sqlVar);
                    $stmtVar->execute([
                        'id_sp' => $productId,
                        'sku' => $variant['sku'],
                        'gia' => $variant['price'] ?: $data['gia_goc'],
                        'sl' => $variant['stock']
                    ]);
                    $variantId = $this->conn->lastInsertId();

                    // 3. Link Variant to Attribute Values
                    if (!empty($variant['attributes'])) {
                        foreach ($variant['attributes'] as $attrId => $valId) {
                            $sqlLink = "INSERT INTO gia_tri_thuoc_tinh_bien_the (id_bien_the, id_gia_tri_thuoc_tinh) 
                                        VALUES (?, ?)";
                            $this->conn->prepare($sqlLink)->execute([$variantId, $valId]);
                        }
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
