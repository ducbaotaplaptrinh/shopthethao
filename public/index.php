<?php
// Kích hoạt thông báo lỗi tối đa để dễ bắt bệnh
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Định nghĩa hằng số BASE_PATH trỏ thẳng về thư mục gốc dự án Shop_TheThao
define('BASE_PATH', dirname(__DIR__));

//3. ĐĂNG KÝ AUTOLOAD TỰ CHẾ THÔNG MINH
spl_autoload_register(function ($className) {
    // Chuyển dấu gạch chéo ngược \ thành gạch chéo xuôi /
    // Ví dụ: "app\models\SanPhamModel" -> "app/models/SanPhamModel.php"
    $file = BASE_PATH . '/' . str_replace('\\', '/', $className) . '.php';

    // Nạp file vật lý vào hệ thống
    if (file_exists($file)) {
        require_once $file;
    }
});

// 4. Khởi chạy Core App
require_once BASE_PATH . '/app/core/App.php';
$app = new App();
$app->run();
