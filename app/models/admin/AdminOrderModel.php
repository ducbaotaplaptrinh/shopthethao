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
    public function updateOrderStatus($id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE don_hang SET trang_thai_don_hang = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
