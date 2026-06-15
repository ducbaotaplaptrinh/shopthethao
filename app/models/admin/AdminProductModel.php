<?php

namespace app\controllers\admin;

use app\core\Model;
use PDO;

class AdminProductController extends Model
{

    public function index(): array
    {
        // Fetch products with their category and brand names
        $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu,
                (SELECT SUM(so_luong_ton) FROM bien_the_san_pham WHERE id_san_pham = sp.id) as tong_ton_kho,
                (SELECT COUNT(*) FROM bien_the_san_pham WHERE id_san_pham = sp.id AND so_luong_ton < 5) as so_bien_the_het_hang
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                ORDER BY sp.id DESC";

        $stmt = $this->conn->query($sql);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'title' => 'Quản lý Sản phẩm | Admin',
            'view' => 'admin/product/index.php',
            'products' => $products
        ];
    }

    public function create(): array
    {
        // Fetch categories and brands for dropdowns
        $categories = $this->conn->query("SELECT id, ten_danh_muc FROM danh_muc")->fetchAll(PDO::FETCH_ASSOC);
        $brands = $this->conn->query("SELECT id, ten_thuong_hieu FROM thuong_hieu")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch dynamic attributes that are variant-enabled (la_bien_the = 1)
        $attributes = [];
        $stmtAttr = $this->conn->query("SELECT * FROM thuoc_tinh WHERE la_bien_the = 1");
        while ($attr = $stmtAttr->fetch(PDO::FETCH_ASSOC)) {
            $stmtVal = $this->conn->prepare("SELECT * FROM gia_tri_thuoc_tinh WHERE id_thuoc_tinh = ?");
            $stmtVal->execute([$attr['id']]);
            $attr['values'] = $stmtVal->fetchAll(PDO::FETCH_ASSOC);
            $attributes[] = $attr;
        }

        return [
            'title' => 'Thêm Sản phẩm mới | Admin',
            'view' => 'admin/product/form.php',
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes
        ];
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->conn->beginTransaction();

                // 1. Insert Base Product
                $sqlProd = "INSERT INTO san_pham (ten_san_pham, duong_dan, id_danh_muc, id_thuong_hieu, mo_ta, gia_goc, la_noi_bat, trang_thai) 
                            VALUES (:ten, :slug, :dm, :th, :mota, :gia, :noibat, :trangthai)";

                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['ten_san_pham'])));

                $stmtProd = $this->conn->prepare($sqlProd);
                $stmtProd->execute([
                    'ten' => $_POST['ten_san_pham'],
                    'slug' => $slug,
                    'dm' => $_POST['id_danh_muc'],
                    'th' => $_POST['id_thuong_hieu'] ?? null,
                    'mota' => $_POST['mo_ta'] ?? '',
                    'gia' => $_POST['gia_goc'] ?? 0,
                    'noibat' => isset($_POST['la_noi_bat']) ? 1 : 0,
                    'trangthai' => isset($_POST['trang_thai']) ? 1 : 0
                ]);

                $productId = $this->conn->lastInsertId();

                // Handle main image upload if any (simplified for this example)
                // In reality, move_uploaded_file logic goes here.

                // 2. Insert Variants
                if (!empty($_POST['variants'])) {
                    $variants = json_decode($_POST['variants'], true);
                    foreach ($variants as $variant) {
                        $sqlVar = "INSERT INTO bien_the_san_pham (id_san_pham, ma_vach_sku, gia_ban, so_luong_ton) 
                                   VALUES (:id_sp, :sku, :gia, :sl)";
                        $stmtVar = $this->conn->prepare($sqlVar);
                        $stmtVar->execute([
                            'id_sp' => $productId,
                            'sku' => $variant['sku'],
                            'gia' => $variant['price'] ?: $_POST['gia_goc'],
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
                header("Location: ?page=admin-products");
                exit;
            } catch (\Exception $e) {
                $this->conn->rollBack();
                die("Error saving product: " . $e->getMessage());
            }
        }
    }
}
