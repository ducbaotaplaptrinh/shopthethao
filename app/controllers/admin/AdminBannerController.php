<?php

namespace app\controllers\admin;

use app\models\BannerModel;

class AdminBannerController
{
    private $model;

    public function __construct()
    {
        $this->model = new BannerModel();
    }

    private function handleFileUpload($fileField, $targetDir = 'assets/images/banners/')
    {
        if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$fileField];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array(strtolower($ext), $allowedExts)) {
            return null;
        }

        $newFileName = 'banner_' . uniqid() . '_' . time() . '.' . $ext;
        $destDir = BASE_PATH . '/public/' . $targetDir;
        $destPath = $destDir . $newFileName;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return $newFileName;
        }

        return null;
    }

    public function index(): array
    {
        $banners = $this->model->getAllBanners();

        return [
            'title' => 'Quản lý Banner | Admin',
            'view' => 'admin/banner/Index.php',
            'banners' => $banners,
            'successMsg' => $_GET['success'] ?? '',
            'errorMsg' => $_GET['error'] ?? ''
        ];
    }

    public function create(): array
    {
        return [
            'title' => 'Thêm Banner Mới | Admin',
            'view' => 'admin/banner/Form.php',
            'isEdit' => false
        ];
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tieuDe = trim($_POST['tieu_de'] ?? '');
            $duongDanLienKet = trim($_POST['duong_dan_lien_ket'] ?? '');
            $viTriHienThi = trim($_POST['vi_tri_hien_thi'] ?? 'slide_chinh');
            $trangThai = isset($_POST['trang_thai']) ? 1 : 0;

            $filename = $this->handleFileUpload('duong_dan_anh');
            if (!$filename) {
                header("Location: ?page=admin-banner-create&error=upload_failed");
                exit;
            }

            $data = [
                'tieu_de' => $tieuDe,
                'duong_dan_anh' => 'assets/images/banners/' . $filename,
                'duong_dan_lien_ket' => $duongDanLienKet,
                'vi_tri_hien_thi' => $viTriHienThi,
                'trang_thai' => $trangThai
            ];

            if ($this->model->storeBanner($data)) {
                header("Location: ?page=admin-banners&success=created");
                exit;
            } else {
                header("Location: ?page=admin-banner-create&error=insert_failed");
                exit;
            }
        }
    }

    public function edit(): array
    {
        $id = intval($_GET['id'] ?? 0);
        $banner = $this->model->getBannerById($id);

        if (!$banner) {
            header("Location: ?page=admin-banners");
            exit;
        }

        return [
            'title' => 'Sửa Banner | Admin',
            'view' => 'admin/banner/Form.php',
            'banner' => $banner,
            'isEdit' => true
        ];
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $banner = $this->model->getBannerById($id);

            if (!$banner) {
                header("Location: ?page=admin-banners");
                exit;
            }

            $tieuDe = trim($_POST['tieu_de'] ?? '');
            $duongDanLienKet = trim($_POST['duong_dan_lien_ket'] ?? '');
            $viTriHienThi = trim($_POST['vi_tri_hien_thi'] ?? 'slide_chinh');
            $trangThai = isset($_POST['trang_thai']) ? 1 : 0;

            // Handle optional new image upload
            $filename = $this->handleFileUpload('duong_dan_anh');
            $duongDanAnh = $banner['duong_dan_anh'];

            if ($filename) {
                // Delete old image if it exists and is a file
                $oldFile = BASE_PATH . '/public/' . $banner['duong_dan_anh'];
                if (file_exists($oldFile) && is_file($oldFile)) {
                    @unlink($oldFile);
                }
                $duongDanAnh = 'assets/images/banners/' . $filename;
            }

            $data = [
                'tieu_de' => $tieuDe,
                'duong_dan_anh' => $duongDanAnh,
                'duong_dan_lien_ket' => $duongDanLienKet,
                'vi_tri_hien_thi' => $viTriHienThi,
                'trang_thai' => $trangThai
            ];

            if ($this->model->updateBanner($id, $data)) {
                header("Location: ?page=admin-banners&success=updated");
                exit;
            } else {
                header("Location: ?page=admin-banner-edit&id={$id}&error=update_failed");
                exit;
            }
        }
    }

    public function delete()
    {
        $id = intval($_GET['id'] ?? 0);
        $banner = $this->model->getBannerById($id);

        if ($banner) {
            // Delete image file from disk
            $file = BASE_PATH . '/public/' . $banner['duong_dan_anh'];
            if (file_exists($file) && is_file($file)) {
                @unlink($file);
            }
            $this->model->deleteBanner($id);
            header("Location: ?page=admin-banners&success=deleted");
            exit;
        }

        header("Location: ?page=admin-banners");
        exit;
    }
}
