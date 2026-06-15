<?php

namespace app\models\admin;

use app\core\Model;
use PDO;

class AdminDashboardModel extends Model
{


    // 1. Thống kê tổng quan


    // Doanh thu (các đơn hàng hoan_thanh)
    public function thongKe()
    {
        $stats = [
            'tongDoanhThu' => 0,
            'donHangCho' => 0,
            'hangHetKho' => 0,
            'tognNguoiDung' => 0
        ];
        //tong danh thu 
        $stmtDT = $this->conn->query("SELECT SUM(tong_thanh_toan) as total FROM don_hang WHERE trang_thai_don_hang = 'hoan_thanh'");
        $stmtDT->execute();
        $stats['tongDoanhThu'] = $stmtDT->fetchColumn() ?: 0;

        // Đơn hàng chờ xử lý (cho_xac_nhan)
        $stmtCho = $this->conn->query("SELECT COUNT(*) FROM don_hang WHERE trang_thai_don_hang = 'cho_xac_nhan'");
        $stats['donHangCho'] = $stmtCho->fetchColumn();

        // Cảnh báo hết hàng (Biến thể số lượng < 5)
        $stmtLowStock = $this->conn->query("SELECT COUNT(*) FROM bien_the_san_pham WHERE so_luong_ton < 5");
        $stats['low_stock_items'] = $stmtLowStock->fetchColumn();

        // Tổng người dùng
        $stmtUsers = $this->conn->query("SELECT COUNT(*) FROM nguoi_dung WHERE vai_tro = 'khach_hang'");
        $stmtUsers->execute();
        $stats['total_users'] = $stmtUsers->fetchColumn();
    }

    // // 2. Doanh thu 7 ngày gần nhất (cho biểu đồ Chart.js)
    // $chartData = [];
    // $chartLabels = [];

    // // Lấy 7 ngày gần nhất
    // $stmtChart = $this->conn->query("
    //     SELECT DATE(ngay_tao) as date, SUM(tong_thanh_toan) as daily_revenue 
    //     FROM don_hang 
    //     WHERE trang_thai_don_hang = 'hoan_thanh' AND ngay_tao >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    //     GROUP BY DATE(ngay_tao)
    //     ORDER BY DATE(ngay_tao) ASC
    // ");
    // $stmtChart->execute();

    // $dailyData = [];
    // while ($row = $stmtChart->fetch(PDO::FETCH_ASSOC)) {
    //     $dailyData[$row['date']] = $row['daily_revenue'];
    // }

    // // Đảm bảo đủ 7 ngày (kể cả ngày không có doanh thu)
    // for ($i = 6; $i >= 0; $i--) {
    //     $date = date('Y-m-d', strtotime("-$i days"));
    //     $chartLabels[] = date('d/m', strtotime($date));
    //     $chartData[] = $dailyData[$date] ?? 0;
    // }

    // return [
    //     'title' => 'Dashboard | Quản trị Bảo Đạt Sport',
    //     'stats' => $stats,
    //     'chartLabels' => json_encode($chartLabels),
    //     'chartData' => json_encode($chartData)
    // ];

}
