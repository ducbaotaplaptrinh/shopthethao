<?php
// 1. Kích hoạt thông báo lỗi tối đa để dễ bắt bệnh
ini_set('display_errors', '1');
error_reporting(E_ALL);

// 2. Định nghĩa hằng số BASE_PATH trỏ thẳng về thư mục gốc dự án Shop_TheThao
define('BASE_PATH', dirname(__DIR__));

// 3. ĐĂNG KÝ AUTOLOAD TỰ CHẾ THÔNG MINH
// spl_autoload_register(function ($className) {
//     // Chuyển dấu gạch chéo ngược \ thành gạch chéo xuôi /
//     // Ví dụ: "app\models\SanPhamModel" -> "app/models/SanPhamModel.php"
//     $file = BASE_PATH . '/' . str_replace('\\', '/', $className) . '.php';

//     // Trường hợp đặc biệt: Nếu hệ thống gọi namespace "app\core\Model" 
//     // nhưng tên file thực tế của bạn là "app/core/App.php" hoặc "app/core/Model.php" (chữ hoa đầu thư mục core)
//     // Đoạn code này sẽ tự động kiểm tra và sửa lỗi vênh chữ hoa/chữ thường trên hệ thống:
//     if (!file_exists($file)) {
//         // Thử thay thế phân đoạn thư mục /core/ thành viết thường hoặc viết hoa nếu cần
//         $file = str_replace('/core/', '/core/', $file); // Giữ nguyên hoặc chỉnh theo cấu trúc của bạn
//     }

//     // Nạp file vật lý vào hệ thống
//     if (file_exists($file)) {
//         require_once $file;
//     }
// });

// 4. Khởi chạy Core App
require_once BASE_PATH . '/app/core/App.php';
$app = new App();
$app->run();
