<?php

namespace app\models;

use app\core\Model;
use app\models\entities\SanPham;
use PDO;


class SanPhamModel extends Model
{
    public function getDanhSachSanPham()
    {
        $sql = "SELECT * FROM san_pham WHERE ngay_xoa is null";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $dulieu = $stmt->fetchAll();
        if (!$dulieu) {
            return null;
        }
        $danhSachEntities = [];
        foreach ($dulieu as $dong) {
            $danhSachEntities[] = new SanPham($dong);
        }
        return $danhSachEntities;
    }
    public function getChiTietSanPham($slugSanPham): ?array
    {
        $sql = "select d.ten_danh_muc, d.duong_dan_slug as category_slug, t.ten_thuong_hieu, s.* from san_pham s 
                join thuong_hieu t 
                on s.ma_thuong_hieu = t.id
                join danh_muc d 
                on d.id = s.ma_danh_muc
                where s.duong_dan_slug = :slug 
                and s.ngay_xoa is null 
                and t.ngay_xoa is null 
                and d.ngay_xoa is null 
                and s.trang_thai = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slugSanPham]);
        $data = $stmt->fetch();
        if (!$data) return null;

        return [
            'item' => new SanPham($data),
            'tenThuongHieu' => $data['ten_thuong_hieu'],
            'tenDanhMuc' => $data['ten_danh_muc'],
            'category_slug' => $data['category_slug'],
        ];
    }
    public function getSPTheoDanhMucThuongHieu($slugDM = null, $slugTH = null): ?array
    {
        $mangSlug = [];
        $sql = "select d.ten_danh_muc, t.ten_thuong_hieu, s.* from san_pham s 
                join thuong_hieu t 
                on s.ma_thuong_hieu = t.id
                join danh_muc d 
                on d.id = s.ma_danh_muc
                where s.ngay_xoa is null 
                and t.ngay_xoa is null 
                and d.ngay_xoa is null 
                and s.trang_thai = 1";
        if (!empty($slugDM)) {
            $sql .= " and d.duong_dan_slug = :slugdm";
            $mangSlug['slugdm'] = $slugDM;
        }
        if (!empty($slugTH)) {
            $sql .= " and t.duong_dan_slug = :slugth";
            $mangSlug['slugth'] = $slugTH;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($mangSlug);
        $data = $stmt->fetchAll();
        $danhSachEntities = [];

        if (empty($data)) {
            return null;
        }
        foreach ($data as $dong) {
            $danhSachEntities[] = [
                'item' => new SanPham($dong),
                'tenThuongHieu' => $dong['ten_thuong_hieu'],
                'tenDanhMuc' => $dong['ten_danh_muc'],
            ];
        }

        return $danhSachEntities;
    }

    //Lấy mega menu
    public function getDanhMucThuongHieu()
    {
        $sql = "select distinct ten_danh_muc, ten_thuong_hieu, d.duong_dan_slug as slug_dm , th.duong_dan_slug as slug_th, d.ma_danh_muc_cha
                from danh_muc d
                join san_pham s on s.ma_danh_muc = d.id
                join thuong_hieu th on th.id = s.ma_thuong_hieu
                where s.trang_thai = 1 
                and th.trang_thai = 1 
                and d.ngay_xoa is null 
                and th.ngay_xoa is null 
                order by d.ma_danh_muc_cha, ten_danh_muc ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $danhSachEntities = [];
        foreach ($data as $dong) {
            $danhSachEntities[] = [
                'slugDM' => $dong['slug_dm'],
                'slugTH' => $dong['slug_th'],
                'tenThuongHieu' => $dong['ten_thuong_hieu'],
                'tenDanhMuc' => $dong['ten_danh_muc'],
            ];
        }
        return $danhSachEntities;
    }
    public function getSanPhamSale()
    {
        $sql = "select *
                from san_pham
                where gia_khuyen_mai > 0 and trang_thai = 1 and ngay_xoa is null";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $danhSachEntities = [];
        foreach ($data as $dong) {
            $danhSachEntities[] = new SanPham($dong);
        }
        return $danhSachEntities;
    }
    public function getSanPhamMoi()
    {
        $sql = "select ten_danh_muc, s.*
                from san_pham s join danh_muc d on d.id = s.ma_danh_muc
                where la_noi_bat = 1
                and ma_danh_muc_cha is not null 
                and s.trang_thai = 1 
                and s.ngay_xoa is null 
                and d.ngay_xoa is null 
                order by ma_danh_muc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $danhSachEntities = [];
        foreach ($data as $dong) {
            $danhSachEntities[] = [
                'item' =>  new SanPham($dong),
                'tenDanhMuc' => $dong['ten_danh_muc'],
            ];
        }
        return $danhSachEntities;
    }

    public function getFilteredProducts($slugDM = '', $slugTH = '', $selectedAttrs = [], $sort = 'newest', $limit = 16, $offset = 0, $keyword = '', bool $onlySale = false): ?array
    {
        $bindParams = [];
        $sql = "SELECT d.ten_danh_muc, t.ten_thuong_hieu, s.* FROM san_pham s
            JOIN thuong_hieu t ON s.ma_thuong_hieu = t.id
            JOIN danh_muc d ON d.id = s.ma_danh_muc
            WHERE s.ngay_xoa IS NULL 
              AND t.ngay_xoa IS NULL 
              AND d.ngay_xoa IS NULL 
              AND s.trang_thai = 1";

        // 1. Lọc theo Danh mục
        if (!empty($slugDM)) {
            $sql .= " AND d.duong_dan_slug = :slugdm";
            $bindParams['slugdm'] = $slugDM;
        }

        // 2. Lọc theo Thương hiệu
        if (!empty($slugTH)) {
            $sql .= " AND t.duong_dan_slug = :slugth";
            $bindParams['slugth'] = $slugTH;
        }

        // 3. Tìm kiếm theo Từ khóa
        if (!empty($keyword)) {
            $sql .= " AND s.ten_san_pham LIKE :keyword";
            $bindParams['keyword'] = '%' . $keyword . '%';
        }

        // 4. Lọc sản phẩm đang giảm giá
        if ($onlySale) {
            $sql .= " AND s.gia_khuyen_mai > 0";
            // LƯU Ý: Nếu đã chuyển gia_khuyen_mai sang bảng biến thể, hãy đổi lại logic check ở đây bằng EXISTS nhé!
        }

        // 5. Lọc theo Thuộc tính (Đã tối ưu, bỏ hoàn toàn bảng cũ)
        if (!empty($selectedAttrs) && is_array($selectedAttrs)) {
            $attrIds = array_map('intval', $selectedAttrs);
            $placeholders = [];
            foreach ($attrIds as $index => $id) {
                $key = "attr_" . $index;
                $placeholders[] = ":" . $key;
                $bindParams[$key] = $id;
            }
            $placeholderStr = implode(',', $placeholders);

            // Chỉ quét qua duy nhất bảng biến thể và bảng trung gian của biến thể
            $sql .= " AND EXISTS (
            SELECT 1 FROM bien_the_san_pham bt 
            JOIN gia_tri_thuoc_tinh_bien_the gtttbt ON gtttbt.ma_bien_the = bt.id 
            WHERE bt.ma_san_pham = s.id 
              AND bt.ngay_xoa IS NULL 
              AND gtttbt.ma_gia_tri_thuoc_tinh IN ($placeholderStr)
        )";
        }


        // Sorting
        switch ($sort) {
            case 'price-asc':
                $sql .= " ORDER BY s.gia_ban ASC";
                break;
            case 'price-desc':
                $sql .= " ORDER BY s.gia_ban DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY s.ngay_tao DESC, s.id DESC";
                break;
        }

        // Pagination
        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        foreach ($bindParams as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        $data = $stmt->fetchAll();

        if (empty($data)) {
            return null;
        }

        $danhSachEntities = [];
        foreach ($data as $dong) {
            $danhSachEntities[] = [
                'item' => new SanPham($dong),
                'tenThuongHieu' => $dong['ten_thuong_hieu'],
                'tenDanhMuc' => $dong['ten_danh_muc'],
            ];
        }

        return $danhSachEntities;
    }

    public function getFilteredProductsCount($slugDM = '', $slugTH = '', $selectedAttrs = [], $keyword = '', bool $onlySale = false): int
    {
        $bindParams = [];
        $sql = "SELECT COUNT(DISTINCT s.id) AS total 
            FROM san_pham s
            JOIN thuong_hieu t ON s.ma_thuong_hieu = t.id
            JOIN danh_muc d ON d.id = s.ma_danh_muc
            WHERE s.ngay_xoa IS NULL 
              AND t.ngay_xoa IS NULL 
              AND d.ngay_xoa IS NULL 
              AND s.trang_thai = 1";

        // 1. Lọc theo Danh mục
        if (!empty($slugDM)) {
            $sql .= " AND d.duong_dan_slug = :slugdm";
            $bindParams['slugdm'] = $slugDM;
        }

        // 2. Lọc theo Thương hiệu
        if (!empty($slugTH)) {
            $sql .= " AND t.duong_dan_slug = :slugth";
            $bindParams['slugth'] = $slugTH;
        }

        // 3. Tìm kiếm theo Từ khóa
        if (!empty($keyword)) {
            $sql .= " AND s.ten_san_pham LIKE :keyword";
            $bindParams['keyword'] = '%' . $keyword . '%';
        }

        // 4. Lọc sản phẩm đang giảm giá
        if ($onlySale) {
            $sql .= " AND s.gia_khuyen_mai > 0";
        }

        // 5. Lọc theo Thuộc tính (Đã loại bỏ hoàn toàn bảng trung gian cũ)
        if (!empty($selectedAttrs) && is_array($selectedAttrs)) {
            $attrIds = array_map('intval', $selectedAttrs); //array_map(): chuyển các id gtbt về dạng số nguyên
            $placeholders = [];
            foreach ($attrIds as $index => $id) {
                $key = "attr_" . $index;
                $placeholders[] = ":" . $key;
                $bindParams[$key] = $id;
            }

            $placeholderStr = implode(',', $placeholders);

            // Chỉ check duy nhất điều kiện EXISTS qua nhánh biến thể
            $sql .= " AND EXISTS (
            SELECT 1 FROM bien_the_san_pham bt 
            JOIN gia_tri_thuoc_tinh_bien_the gtttbt ON gtttbt.ma_bien_the = bt.id 
            WHERE bt.ma_san_pham = s.id 
              AND bt.ngay_xoa IS NULL 
              AND gtttbt.ma_gia_tri_thuoc_tinh IN ($placeholderStr)
        )";
        }

        try {
            $stmt = $this->conn->prepare($sql);
            foreach ($bindParams as $key => $val) {
                $stmt->bindValue(':' . $key, $val);
            }

            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $row ? (int)$row['total'] : 0;
        } catch (\PDOException $e) {
            // Trả về 0 nếu có lỗi database xảy ra để giao diện không bị sập crash
            return 0;
        }
    }

    public function getAnhSanPham($idSanPham): array
    {
        $sql = "SELECT * FROM anh_san_pham WHERE ma_san_pham = :id ORDER BY thu_tu_sap_xep ASC, id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $idSanPham]);
        return $stmt->fetchAll() ?: [];
    }

    public function getReviews($idSanPham): array
    {
        $sql = "SELECT dg.*, nd.ho_ten, nd.anh_dai_dien 
                FROM danh_gia_san_pham dg 
                JOIN nguoi_dung nd ON nd.id = dg.ma_nguoi_dung 
                WHERE dg.ma_san_pham = :id AND dg.trang_thai = 1 
                ORDER BY dg.ngay_tao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $idSanPham]);
        return $stmt->fetchAll() ?: [];
    }

    public function getBienTheSanPham($idSanPham): array
    {
        $sql = "SELECT bt.* 
                FROM bien_the_san_pham bt 
                WHERE bt.ma_san_pham = :id AND bt.trang_thai = 1 AND bt.ngay_xoa IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $idSanPham]);
        $variations = $stmt->fetchAll() ?: [];

        foreach ($variations as &$bt) {
            $sqlAttr = "SELECT tt.ten_thuoc_tinh, gt.gia_tri, gt.id as gia_tri_id
                        FROM gia_tri_thuoc_tinh_bien_the gtttbt
                        JOIN gia_tri_thuoc_tinh gt ON gt.id = gtttbt.ma_gia_tri_thuoc_tinh
                        JOIN thuoc_tinh tt ON tt.id = gt.ma_thuoc_tinh
                        WHERE gtttbt.ma_bien_the = :ma_bien_the";
            $stmtAttr = $this->conn->prepare($sqlAttr);
            $stmtAttr->execute(['ma_bien_the' => $bt['id']]);
            $bt['attributes'] = $stmtAttr->fetchAll() ?: [];
        }

        return $variations;
    }

    public function getCartItemDetails($productId, $variationId = null): ?array
    {
        // Fetch product info
        $sql = "SELECT id, ten_san_pham, anh_dai_dien, gia_ban, gia_khuyen_mai, so_luong_ton 
                FROM san_pham 
                WHERE id = :id AND ngay_xoa IS NULL AND trang_thai = 1 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $productId]);
        $prod = $stmt->fetch();
        if (!$prod) return null;

        $name = $prod['ten_san_pham'];
        $image = $prod['anh_dai_dien'];
        $price = ($prod['gia_khuyen_mai'] > 0) ? $prod['gia_khuyen_mai'] : $prod['gia_ban'];
        $attributes = "";
        $stock = (int)$prod['so_luong_ton'];

        // If variation is set, fetch variation info
        if (!empty($variationId)) {
            $sqlVar = "SELECT id, gia_ban_rieng, anh_rieng, so_luong_ton FROM bien_the_san_pham WHERE id = :id LIMIT 1";
            $stmtVar = $this->conn->prepare($sqlVar);
            $stmtVar->execute(['id' => $variationId]);
            $var = $stmtVar->fetch();
            if ($var) {
                $stock = (int)$var['so_luong_ton'];
                if ($var['gia_ban_rieng'] > 0) {
                    $price = $var['gia_ban_rieng'];
                    // Apply discount if parent product is on sale
                    if ($prod['gia_khuyen_mai'] > 0) {
                        $discountRate = ($prod['gia_ban'] - $prod['gia_khuyen_mai']) / $prod['gia_ban'];
                        $price = round($price * (1 - $discountRate));
                    }
                }
                if (!empty($var['anh_rieng'])) {
                    $image = $var['anh_rieng'];
                }

                // Get attributes names and values
                $sqlAttr = "SELECT tt.ten_thuoc_tinh, gt.gia_tri 
                            FROM gia_tri_thuoc_tinh_bien_the gtttbt
                            JOIN gia_tri_thuoc_tinh gt ON gt.id = gtttbt.ma_gia_tri_thuoc_tinh
                            JOIN thuoc_tinh tt ON tt.id = gt.ma_thuoc_tinh
                            WHERE gtttbt.ma_bien_the = :id";
                $stmtAttr = $this->conn->prepare($sqlAttr);
                $stmtAttr->execute(['id' => $variationId]);
                $attrsData = $stmtAttr->fetchAll() ?: [];

                $attrsList = [];
                foreach ($attrsData as $attr) {
                    $attrsList[] = $attr['ten_thuoc_tinh'] . ': ' . $attr['gia_tri'];
                }
                $attributes = implode(', ', $attrsList);
            }
        }

        return [
            'product_id' => (int)$productId,
            'variation_id' => $variationId ? (int)$variationId : null,
            'name' => $name,
            'image' => $image,
            'price' => (float)$price,
            'attributes' => $attributes,
            'so_luong_ton' => $stock
        ];
    }

    public function getSearchSuggestions(string $keyword): array
    {
        $sql = "SELECT * FROM san_pham 
                WHERE ten_san_pham LIKE :keyword 
                AND trang_thai = 1 
                AND ngay_xoa IS NULL 
                LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['keyword' => '%' . $keyword . '%']);
        $dulieu = $stmt->fetchAll() ?: [];

        $danhSachEntities = [];
        foreach ($dulieu as $dong) {
            $danhSachEntities[] = new SanPham($dong);
        }
        return $danhSachEntities;
    }
}
