<?php

namespace app\models\admin;

use app\core\Model;
use PDO;

class AdminReviewModel extends Model
{
    /**
     * Lấy toàn bộ đánh giá sản phẩm kèm bộ lọc
     */
    public function getAllReviews(string $star = '', string $status = '', string $keyword = ''): array
    {
        $sql = "SELECT dg.*, nd.ho_ten as reviewer_name, nd.email as reviewer_email, nd.anh_dai_dien as reviewer_avatar, sp.ten_san_pham, sp.anh_dai_dien as product_image
                FROM danh_gia_san_pham dg
                JOIN nguoi_dung nd ON dg.ma_nguoi_dung = nd.id
                JOIN san_pham sp ON dg.ma_san_pham = sp.id";
        
        $whereClauses = [];
        $params = [];

        if ($star !== '') {
            $whereClauses[] = "dg.diem_so = :star";
            $params['star'] = (int)$star;
        }

        if ($status !== '') {
            $whereClauses[] = "dg.trang_thai = :status";
            $params['status'] = (int)$status;
        }

        if ($keyword !== '') {
            $whereClauses[] = "(nd.ho_ten LIKE :keyword OR nd.email LIKE :keyword OR sp.ten_san_pham LIKE :keyword OR dg.binh_luan LIKE :keyword)";
            $params['keyword'] = '%' . $keyword . '%';
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $sql .= " ORDER BY dg.ngay_tao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Lấy chi tiết một đánh giá theo ID
     */
    public function getReviewById(int $id): ?array
    {
        $sql = "SELECT dg.*, nd.ho_ten as reviewer_name, nd.email as reviewer_email, sp.ten_san_pham, sp.anh_dai_dien as product_image
                FROM danh_gia_san_pham dg
                JOIN nguoi_dung nd ON dg.ma_nguoi_dung = nd.id
                JOIN san_pham sp ON dg.ma_san_pham = sp.id
                WHERE dg.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    /**
     * Cập nhật đánh giá (sửa điểm số, bình luận, trạng thái)
     */
    public function updateReview(int $id, int $diemSo, string $binhLuan, int $trangThai): bool
    {
        $sql = "UPDATE danh_gia_san_pham 
                SET diem_so = :score, binh_luan = :comment, trang_thai = :status 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'score' => $diemSo,
            'comment' => !empty($binhLuan) ? $binhLuan : null,
            'status' => $trangThai
        ]);
    }

    /**
     * Xóa đánh giá khỏi database
     */
    public function deleteReview(int $id): bool
    {
        $sql = "DELETE FROM danh_gia_san_pham WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Bật/Tắt ẩn hiện nhanh đánh giá
     */
    public function toggleReviewStatus(int $id): bool
    {
        $sql = "UPDATE danh_gia_san_pham SET trang_thai = 1 - trang_thai WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Thêm đánh giá mới thủ công từ phía Admin (cho việc seeding hoặc phản hồi mẫu)
     */
    public function createReview(int $userId, int $productId, int $diemSo, string $binhLuan): bool
    {
        $sql = "INSERT INTO danh_gia_san_pham (ma_nguoi_dung, ma_san_pham, diem_so, binh_luan, trang_thai, ngay_tao)
                VALUES (:uid, :pid, :score, :comment, 1, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'uid' => $userId,
            'pid' => $productId,
            'score' => $diemSo,
            'comment' => !empty($binhLuan) ? $binhLuan : null
        ]);
    }
}
