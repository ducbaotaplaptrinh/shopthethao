<?php

namespace app\controllers\admin;

use app\models\admin\AdminAttributeModel;

class AdminAttributeController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminAttributeModel();
    }

    public function index(): array
    {
        $attributes = $this->model->getAllAttributesWithValues();

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

            $this->model->insertAttributeGroup($ten, $labienthe);
            
            header("Location: ?page=admin-attributes");
            exit;
        }
    }

    public function storeValue()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_thuoc_tinh = $_POST['id_thuoc_tinh'];
            $gia_tri = $_POST['gia_tri'];

            $this->model->insertAttributeValue($id_thuoc_tinh, $gia_tri);
            
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
                $this->model->toggleVariantStatus($id, $status);
                echo json_encode(['success' => true]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }
}
