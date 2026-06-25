<?php

namespace app\core;

use PDO;
use PDOException;

class Model
{
    public $conn;
    public function __construct()
    {
        // $host = "localhost";
        // $dbName = "bd_baodatsport";
        // $userName = "root";
        // $password = "";
        $host = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com";
        $port = "4000";
        $dbName = "bd_baodatsport";
        $userName = "3NCDKG8eN9oMkmX.root";
        $password = "caKjtcMgEHyM2Vgx";
        $option = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_CA => BASE_PATH . '/config/isgrootx1.pem',
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ];
        try {
            $this->conn = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4",
                $userName,
                $password,
                $option
            );
        } catch (PDOException  $er) {
            die("Lỗi kết nối dữ liệu: " . $er->getMessage());
        }
    }
}
