<?php
class Database
{
    public $conn;

    public function __construct()
    {
        $host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
        $port = '4000';
        $dbname = 'bd_baodatsport'; // Xem kỹ lưu ý số 3 bên dưới
        $username = '3NCDKG8eN9oMkmX.root';
        $password = 'caKjtcMgEHyM2Vgx';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        try {
            $options = [
                PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/isgrootx1.pem',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ];
            $this->conn = new PDO($dsn, $username, $password, $options);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
        }
    }
}
?>