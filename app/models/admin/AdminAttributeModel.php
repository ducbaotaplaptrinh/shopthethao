<?php

namespace app\controllers\admin;

use app\core\Model;
use PDO;

class AdminAttributeController extends Model
{

    public function index(): array
    {
        // Lấy danh sách nhóm thuộc tính
        $stmt = $this->conn->prepare("SELECT * FROM thuoc_tinh ORDER BY id DESC");
        $attributes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy danh sách giá trị thuộc tính cho từng nhóm
        foreach ($attributes as &$attr) {
            $stmtVal = $this->conn->prepare("SELECT * FROM gia_tri_thuoc_tinh WHERE id_thuoc_tinh = ?");
            $stmtVal->execute([$attr['id']]);
            $attr['values'] = $stmtVal->fetchAll(PDO::FETCH_ASSOC);
        }

        return [
            'title' => 'Quản lý Thuộc tính | Admin',
            'view' => 'admin/attribute/index.php',
            'attributes' => $attributes
        ];
    }

    public function storeGroup()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten = $_POST['ten_thuoc_tinh'];
            $labienthe = isset($_POST['la_bien_the']) ? 1 : 0;

            $stmt = $this->conn->prepare("INSERT INTO thuoc_tinh (ten_thuoc_tinh, la_bien_the) VALUES (?, ?)");
            $stmt->execute([$ten, $labienthe]);

            header("Location: ?page=admin-attributes");
            exit;
        }
    }

    public function storeValue()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_thuoc_tinh = $_POST['id_thuoc_tinh'];
            $gia_tri = $_POST['gia_tri'];

            $stmt = $this->conn->prepare("INSERT INTO gia_tri_thuoc_tinh (id_thuoc_tinh, gia_tri) VALUES (?, ?)");
            $stmt->execute([$id_thuoc_tinh, $gia_tri]);

            header("Location: ?page=admin-attributes");
            exit;
        }
    }

    public function toggleVariant()
    {
        // AJAX endpoint để bật tắt trạng thái la_bien_the
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? null;
            $status = $data['status'] ?? 0;

            if ($id) {
                $stmt = $this->conn->prepare("UPDATE thuoc_tinh SET la_bien_the = ? WHERE id = ?");
                $stmt->execute([$status, $id]);
                echo json_encode(['success' => true]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }
}
