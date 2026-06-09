<?php

namespace app\core;

use PDO;
use PDOException;

class Model
{
    protected $conn;
    public function __construct()
    {
        $localHost = "localhost";
        $dbName = "bd_baodatsport";
        $userName = "root";
        $password = "";
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
