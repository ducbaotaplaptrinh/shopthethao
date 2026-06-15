<?php

namespace app\models;

use app\core\Model;
use PDO;

class NewsModel extends Model
{
    /**
     * Lấy danh sách tất cả tin tức (hỗ trợ tìm kiếm theo tiêu đề hoặc tóm tắt)
     * Kết nối với bảng `nguoi_dung` để lấy họ tên tác giả.
     * 
     * @param string|null $search Từ khóa tìm kiếm
     * @return array Trả về mảng chứa danh sách bài viết
     */
    public function getAllNews($search = null): array
    {
        // Sử dụng cột thực tế: duong_dan_slug, anh_dai_dien, tom_tat, ngay_tao, ma_tac_gia
        // LEFT JOIN với bảng nguoi_dung để lấy ho_ten tác giả
        $sql = "SELECT t.id, t.tieu_de, t.duong_dan_slug, t.anh_dai_dien, t.tom_tat, t.noi_dung, t.luot_xem, t.ngay_tao, 
                       COALESCE(n.ho_ten, 'Admin') AS tac_gia
                FROM tin_tuc t
                LEFT JOIN nguoi_dung n ON t.ma_tac_gia = n.id
                WHERE t.trang_thai = 1";
        $params = [];
        
        // Lọc tìm kiếm theo tiêu đề hoặc tóm tắt bài viết
        if ($search !== null && $search !== '') {
            $sql .= " AND (t.tieu_de LIKE :search OR t.tom_tat LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        
        // Sắp xếp bài viết mới nhất lên đầu
        $sql .= " ORDER BY t.ngay_tao DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Lấy chi tiết một bài viết tin tức dựa theo đường dẫn slug (duong_dan_slug)
     * 
     * @param string $slug Đường dẫn thân thiện SEO
     * @return array|null Chi tiết bài viết hoặc null nếu không tìm thấy
     */
    public function getNewsBySlug($slug): ?array
    {
        $sql = "SELECT t.*, COALESCE(n.ho_ten, 'Admin') AS tac_gia
                FROM tin_tuc t
                LEFT JOIN nguoi_dung n ON t.ma_tac_gia = n.id
                WHERE t.duong_dan_slug = :slug AND t.trang_thai = 1 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ?: null;
    }

    /**
     * Tăng số lượt xem của bài viết lên 1 đơn vị khi có người dùng truy cập
     * 
     * @param int $id Mã bài viết
     */
    public function incrementViews($id): void
    {
        $sql = "UPDATE tin_tuc SET luot_xem = luot_xem + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    /**
     * Lấy danh sách tin tức liên quan (các bài viết mới khác)
     * 
     * @param int $excludeId ID của bài viết hiện tại cần loại trừ
     * @param int $limit Số lượng bài viết liên quan muốn lấy
     * @return array Danh sách bài viết liên quan
     */
    public function getRelatedNews($excludeId, $limit = 3): array
    {
        $sql = "SELECT t.id, t.tieu_de, t.duong_dan_slug, t.anh_dai_dien, t.tom_tat, t.ngay_tao,
                       COALESCE(n.ho_ten, 'Admin') AS tac_gia
                FROM tin_tuc t
                LEFT JOIN nguoi_dung n ON t.ma_tac_gia = n.id
                WHERE t.id != :excludeId AND t.trang_thai = 1 
                ORDER BY t.ngay_tao DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(':excludeId', (int)$excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
