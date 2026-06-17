<?php

namespace app\models\admin;

use app\core\Model;
use PDO;

class AdminOrderModel extends Model
{
    public function getAllOrders()
    {
        $sql = "SELECT dh.*, nd.ho_ten, nd.email 
                FROM don_hang dh 
                LEFT JOIN nguoi_dung nd ON dh.ma_nguoi_dung = nd.id 
                ORDER BY dh.id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id)
    {
        $sqlOrder = "SELECT dh.*, nd.ho_ten, nd.email, nd.so_dien_thoai as sdt_user
                     FROM don_hang dh 
                     LEFT JOIN nguoi_dung nd ON dh.ma_nguoi_dung = nd.id 
                     WHERE dh.id = ?";
        $stmtOrder = $this->conn->prepare($sqlOrder);
        $stmtOrder->execute([$id]);
        return $stmtOrder->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($orderId)
    {
        $sqlItems = "SELECT ct.*, sp.ten_san_pham, bt.ma_vach_sku 
                     FROM chi_tiet_don_hang ct
                     LEFT JOIN bien_the_san_pham bt ON ct.ma_bien_the = bt.id
                     LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.id
                     WHERE ct.ma_don_hang = ?";
        $stmtItems = $this->conn->prepare($sqlItems);
        $stmtItems->execute([$orderId]);
        return $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái đơn hàng + xử lý kho trong 1 transaction.
     * - Khi chuyển sang 'dang_xu_ly': trừ kho
     * - Khi chuyển sang 'da_huy': hoàn kho (nếu đã từng trừ)
     *
     * @param int    $id         ID đơn hàng
     * @param string $trangThaiMoi Trạng thái mới
     * @param string $trangThaiCu  Trạng thái hiện tại (để quyết định hoàn kho)
     * @return bool
     */
    public function capNhatTrangThaiDonHang($id, $trangThaiMoi, $trangThaiCu)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Cập nhật trạng thái đơn hàng
            $stmt = $this->conn->prepare("UPDATE don_hang SET trang_thai_don_hang = ? WHERE id = ?");
            $stmt->execute([$trangThaiMoi, $id]);

            // 2. Xử lý kho hàng
            if ($trangThaiMoi === 'dang_xu_ly') {
                // Trừ kho: khi xác nhận đơn hàng (cho_xac_nhan → dang_xu_ly)
                $this->truKho($id);
            } elseif ($trangThaiMoi === 'da_huy') {
                // Hoàn kho: chỉ hoàn lại nếu đã từng trừ (tức là đã qua trạng thái dang_xu_ly)
                $trangThaiDaTruKho = ['dang_xu_ly', 'dang_giao', 'hoan_thanh'];
                if (in_array($trangThaiCu, $trangThaiDaTruKho)) {
                    $this->hoanKho($id);
                }
            }

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Trừ kho: giảm so_luong_ton của các biến thể theo chi tiết đơn hàng.
     * Nếu biến thể nào không đủ hàng, ném Exception để rollback.
     */
    private function truKho($orderId)
    {
        $items = $this->getOrderItems($orderId);

        foreach ($items as $item) {
            $maVach   = $item['ma_bien_the'];
            $soLuong  = (int)$item['so_luong'];

            // Kiểm tra tồn kho trước khi trừ
            $stmtCheck = $this->conn->prepare(
                "SELECT so_luong_ton FROM bien_the_san_pham WHERE id = ? FOR UPDATE"
            );
            $stmtCheck->execute([$maVach]);
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$row || $row['so_luong_ton'] < $soLuong) {
                throw new \Exception("Biến thể ID {$maVach} không đủ tồn kho (cần {$soLuong}, còn {$row['so_luong_ton']}).");
            }

            // Trừ kho
            $stmtTru = $this->conn->prepare(
                "UPDATE bien_the_san_pham SET so_luong_ton = so_luong_ton - ? WHERE id = ?"
            );
            $stmtTru->execute([$soLuong, $maVach]);
        }
    }

    /**
     * Hoàn kho: cộng lại so_luong_ton khi đơn hàng bị hủy.
     */
    private function hoanKho($orderId)
    {
        $items = $this->getOrderItems($orderId);

        foreach ($items as $item) {
            $maVach  = $item['ma_bien_the'];
            $soLuong = (int)$item['so_luong'];

            $stmt = $this->conn->prepare(
                "UPDATE bien_the_san_pham SET so_luong_ton = so_luong_ton + ? WHERE id = ?"
            );
            $stmt->execute([$soLuong, $maVach]);
        }
    }

    // Giữ lại hàm cũ để tương thích (alias — không xử lý kho)
    public function updateOrderStatus($id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE don_hang SET trang_thai_don_hang = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Xóa đơn hàng (xóa thực sự - chỉ cho phép với đơn đã hủy)
    public function xoaDonHang($id)
    {
        // Xóa chi tiết đơn hàng trước
        $stmtChiTiet = $this->conn->prepare("DELETE FROM chi_tiet_don_hang WHERE ma_don_hang = ?");
        $stmtChiTiet->execute([$id]);

        // Xóa đơn hàng
        $stmtDon = $this->conn->prepare("DELETE FROM don_hang WHERE id = ?");
        return $stmtDon->execute([$id]);
    }
}
