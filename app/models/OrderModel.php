<?php

namespace app\models;

use app\core\Model;
use PDO;
use PDOException;
use Exception;
use app\models\entities\DonHang;
use app\models\entities\ChiTietDonHang;

class OrderModel extends Model
{
    public function getOrCreateUser($ho_ten, $email, $so_dien_thoai): int
    {
        // 1. Check if user already exists by email or phone
        $sql = "SELECT id FROM nguoi_dung WHERE so_dien_thoai = :phone OR (email = :email AND email IS NOT NULL) LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'phone' => $so_dien_thoai,
            'email' => !empty($email) ? $email : null
        ]);
        $row = $stmt->fetch();
        if ($row) {
            return (int)$row['id'];
        }

        // 2. Auto-create guest user
        $sqlInsert = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, vai_tro, trang_thai) 
                      VALUES (:name, :email, :password, :phone, 'khach_hang', 1)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        
        $guestEmail = !empty($email) ? $email : 'guest_' . time() . '_' . rand(100, 999) . '@sportpro.vn';
        $dummyPassword = password_hash('guest_sportpro_123', PASSWORD_DEFAULT);

        $stmtInsert->execute([
            'name' => $ho_ten,
            'email' => $guestEmail,
            'password' => $dummyPassword,
            'phone' => $so_dien_thoai
        ]);

        return (int)$this->conn->lastInsertId();
    }

    public function placeOrder(DonHang $order, $cartItems): string
    {
        try {
            $this->conn->beginTransaction();

            // 1. Get or create user ID if not explicitly set
            $userId = $order->getMa_nguoi_dung();
            if ($userId <= 0) {
                $userId = $this->getOrCreateUser(
                    $order->getHo_ten_nguoi_nhan(),
                    $order->getEmail(),
                    $order->getSo_dien_thoai()
                );
                $order->setMa_nguoi_dung($userId);
            }

            // 2. Generate unique order code
            $orderCode = 'DH-' . strtoupper(dechex(time())) . rand(10, 99);
            $order->setMa_don_hang($orderCode);

            // 3. Insert into don_hang
            $sqlOrder = "INSERT INTO don_hang (
                            ma_nguoi_dung, ma_don_hang, ho_ten_nguoi_nhan, so_dien_thoai, email, 
                            dia_chi_giao_hang, ghi_chu, tong_tien_hang, phi_van_chuyen, tien_giam_gia, 
                            tong_thanh_toan, phuong_thuc_thanh_toan, trang_thai_thanh_toan, trang_thai_don_hang
                         ) VALUES (
                            :user_id, :order_code, :recipient_name, :phone, :email, 
                            :address, :notes, :subtotal, :shipping, :discount, 
                            :total, :payment_method, :payment_status, :order_status
                         )";
            
            $stmtOrder = $this->conn->prepare($sqlOrder);
            $stmtOrder->execute([
                'user_id' => $order->getMa_nguoi_dung(),
                'order_code' => $order->getMa_don_hang(),
                'recipient_name' => $order->getHo_ten_nguoi_nhan(),
                'phone' => $order->getSo_dien_thoai(),
                'email' => $order->getEmail(),
                'address' => $order->getDia_chi_giao_hang(),
                'notes' => $order->getGhi_chu(),
                'subtotal' => $order->getTong_tien_hang(),
                'shipping' => $order->getPhi_van_chuyen(),
                'discount' => $order->getTien_giam_gia(),
                'total' => $order->getTong_thanh_toan(),
                'payment_method' => $order->getPhuong_thuc_thanh_toan(),
                'payment_status' => $order->getTrang_thai_thanh_toan(),
                'order_status' => $order->getTrang_thai_don_hang()
            ]);

            $orderId = (int)$this->conn->lastInsertId();
            $order->setId($orderId);

            // 4. Insert into chi_tiet_don_hang and deduct stock
            foreach ($cartItems as $item) {
                $subtotalItem = $item['price'] * $item['qty'];
                
                $sqlItem = "INSERT INTO chi_tiet_don_hang (
                                ma_don_hang, ma_san_pham, ma_bien_the, ten_san_pham, 
                                thong_tin_bien_the, anh_dai_dien, gia_mua, so_luong, thanh_tien
                            ) VALUES (
                                :order_id, :product_id, :variation_id, :product_name, 
                                :variation_info, :image, :price, :qty, :thanh_tien
                            )";
                
                $stmtItem = $this->conn->prepare($sqlItem);
                $stmtItem->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'variation_id' => !empty($item['variation_id']) ? $item['variation_id'] : null,
                    'product_name' => $item['name'],
                    'variation_info' => !empty($item['attributes']) ? $item['attributes'] : null,
                    'image' => $item['image'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'thanh_tien' => $subtotalItem
                ]);

                // Stock deduction
                if (!empty($item['variation_id'])) {
                    // Update variation stock
                    $sqlUpdateVar = "UPDATE bien_the_san_pham 
                                     SET so_luong_ton = GREATEST(0, so_luong_ton - :qty) 
                                     WHERE id = :var_id";
                    $stmtUpdateVar = $this->conn->prepare($sqlUpdateVar);
                    $stmtUpdateVar->execute([
                        'qty' => $item['qty'],
                        'var_id' => $item['variation_id']
                    ]);
                }

                // Update general product stock
                $sqlUpdateProd = "UPDATE san_pham 
                                  SET so_luong_ton = GREATEST(0, so_luong_ton - :qty) 
                                  WHERE id = :prod_id";
                $stmtUpdateProd = $this->conn->prepare($sqlUpdateProd);
                $stmtUpdateProd->execute([
                    'qty' => $item['qty'],
                    'prod_id' => $item['product_id']
                ]);
            }

            $this->conn->commit();
            return $orderCode;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function getOrderDetails($ma_don_hang): ?array
    {
        $sql = "SELECT * FROM don_hang WHERE ma_don_hang = :code LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['code' => $ma_don_hang]);
        $orderRow = $stmt->fetch();
        if (!$orderRow) {
            return null;
        }

        $orderEntity = new DonHang($orderRow);

        $sqlItems = "SELECT * FROM chi_tiet_don_hang WHERE ma_don_hang = :id";
        $stmtItems = $this->conn->prepare($sqlItems);
        $stmtItems->execute(['id' => $orderEntity->getId()]);
        $itemRows = $stmtItems->fetchAll() ?: [];

        $itemEntities = [];
        foreach ($itemRows as $row) {
            $itemEntities[] = new ChiTietDonHang($row);
        }

        return [
            'order' => $orderEntity,
            'items' => $itemEntities
        ];
    }

    public function trackOrder($phoneOrOrderId): array
    {
        $term = trim($phoneOrOrderId);
        $sql = "SELECT * FROM don_hang 
                WHERE ma_don_hang = :term OR so_dien_thoai = :term 
                ORDER BY ngay_tao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['term' => $term]);
        $rows = $stmt->fetchAll() ?: [];

        $orderEntities = [];
        foreach ($rows as $row) {
            $orderEntities[] = new DonHang($row);
        }
        return $orderEntities;
    }
}
