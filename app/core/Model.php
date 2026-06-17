<?php

namespace app\core;

use PDO;
use PDOException;

class Model
{
    protected $conn;
    public function __construct()
    {
        // Tự động nhận diện môi trường Localhost hoặc Production (InfinityFree)
        $isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) || 
                   (strpos($_SERVER['HTTP_HOST'] ?? '', '192.168.') === 0);

        if ($isLocal) {
            // Cấu hình Database Localhost (XAMPP)
            $localHost = "localhost";
            $dbName = "bd_baodatsport";
            $userName = "root";
            $password = "";
        } else {
            // Cấu hình Database Hosting (InfinityFree)
            $localHost = "sql103.infinityfree.com";
            $dbName = "if0_42192939_bao_dat_sport";
            $userName = "if0_42192939";
            $password = "AY3cLd8uNlfK7";
        }

        $option = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try {
            $this->conn = new PDO(
                "mysql:host=$localHost;dbname=$dbName;charset=utf8mb4",
                $userName,
                $password,
                $option
            );
        } catch (PDOException  $er) {
            die("Lỗi kết nối dữ liệu: " . $er->getMessage());
        }
    }
}
