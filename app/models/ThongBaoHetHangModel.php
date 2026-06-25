<?php

namespace app\models;

use app\core\Model;
use PDO;
use PDOException;

class ThongBaoHetHangModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists()
    {
        $sql = "CREATE TABLE IF NOT EXISTS thong_bao_het_hang (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ma_san_pham INT NOT NULL,
            ma_bien_the INT NULL,
            email VARCHAR(255) NOT NULL,
            ngay_dang_ky DATETIME DEFAULT CURRENT_TIMESTAMP,
            trang_thai INT DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            // Ghi log lỗi nếu cần, nhưng không chặn chương trình chạy
            error_log("Lỗi tạo bảng thong_bao_het_hang: " . $e->getMessage());
        }
    }

    public function luuDangKy(int $productId, ?int $variationId, string $email): array
    {
        try {
            // Kiểm tra xem email này đã đăng ký nhận thông báo cho sản phẩm/biến thể này chưa (và chưa gửi thông báo - trang_thai = 0)
            $sqlCheck = "SELECT COUNT(*) FROM thong_bao_het_hang 
                         WHERE ma_san_pham = :product_id 
                           AND (ma_bien_the = :variation_id OR (ma_bien_the IS NULL AND :variation_id_check IS NULL)) 
                           AND email = :email 
                           AND trang_thai = 0";

            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([
                'product_id' => $productId,
                'variation_id' => $variationId,
                'variation_id_check' => $variationId,
                'email' => $email
            ]);

            if ($stmtCheck->fetchColumn() > 0) {
                return [
                    'success' => false,
                    'message' => 'Email của bạn đã đăng ký nhận thông báo cho sản phẩm này trước đó rồi!'
                ];
            }

            // Lưu đăng ký mới
            $sqlInsert = "INSERT INTO thong_bao_het_hang (ma_san_pham, ma_bien_the, email) 
                          VALUES (:product_id, :variation_id, :email)";
            $stmtInsert = $this->conn->prepare($sqlInsert);
            $stmtInsert->execute([
                'product_id' => $productId,
                'variation_id' => $variationId,
                'email' => $email
            ]);

            return [
                'success' => true,
                'message' => 'Đăng ký thành công! Chúng tôi sẽ thông báo cho bạn qua email ngay khi sản phẩm có hàng.'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra trong quá trình lưu thông tin. Vui lòng thử lại sau.'
            ];
        }
    }
}
