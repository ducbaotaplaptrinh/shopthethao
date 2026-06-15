<?php

namespace app\controllers\admin;

use app\core\Model;
use PDO;

class AdminCustomerController extends Model
{


    public function index(): array
    {
        $sql = "SELECT nd.*, ht.ten_hang, ht.mau_sac, ht.bieu_tuong,
                (SELECT COUNT(*) FROM don_hang WHERE id_nguoi_dung = nd.id) as so_don_hang
                FROM nguoi_dung nd
                LEFT JOIN hang_thanh_vien ht ON nd.ma_hang = ht.id
                WHERE nd.vai_tro = 'khach_hang'
                ORDER BY nd.tong_chi_tieu DESC";

        $stmt = $this->conn->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch membership tiers for display reference
        $tiers = $this->conn->query("SELECT * FROM hang_thanh_vien ORDER BY muc_chi_tieu_toi_thieu ASC")->fetchAll(PDO::FETCH_ASSOC);

        return [
            'title' => 'Quản lý Khách hàng | Admin',
            'view' => 'admin/customer/index.php',
            'customers' => $customers,
            'tiers' => $tiers
        ];
    }

    public function toggleStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $status = $_POST['trang_thai'];

            $stmt = $this->conn->prepare("UPDATE nguoi_dung SET trang_thai = ? WHERE id = ?");
            $stmt->execute([$status, $id]);

            header("Location: ?page=admin-customers");
            exit;
        }
    }
}
