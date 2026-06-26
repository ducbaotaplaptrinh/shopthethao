<?php

namespace app\controllers\admin;

use app\models\admin\AdminCategoryBrandModel;

class AdminCategoryBrandController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminCategoryBrandModel();
    }

    // Kiểm tra quyền admin
    private function kiemTraAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'quan_tri') {
            header("Location: ?page=login");
            exit;
        }
    }

    /** Tạo slug từ tên (hỗ trợ tiếng Việt cơ bản) */
    private function taoSlug($str)
    {
        $str = mb_strtolower(trim($str), 'UTF-8');
        $str = preg_replace('/[àáạảãâầấậẩẫăằắặẳẵ]/u', 'a', $str);
        $str = preg_replace('/[èéẹẻẽêềếệểễ]/u', 'e', $str);
        $str = preg_replace('/[ìíịỉĩ]/u', 'i', $str);
        $str = preg_replace('/[òóọỏõôồốộổỗơờớợởỡ]/u', 'o', $str);
        $str = preg_replace('/[ùúụủũưừứựửữ]/u', 'u', $str);
        $str = preg_replace('/[ỳýỵỷỹ]/u', 'y', $str);
        $str = preg_replace('/[đ]/u', 'd', $str);
        $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
        $str = preg_replace('/[\s-]+/', '-', $str);
        return trim($str, '-');
    }

    // =========================================================
    // DANH MỤC
    // =========================================================

    public function categories(): array
    {
        $this->kiemTraAdmin();
        $categories = $this->model->getAllCategories();

        return [
            'title'      => 'Quản lý Danh mục | Admin',
            'view'       => 'admin/category/index.php',
            'categories' => $categories
        ];
    }

    public function storeCategory()
    {
        $this->kiemTraAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=admin-categories");
            exit;
        }

        $ten      = trim($_POST['ten_danh_muc'] ?? '');
        $slug     = !empty(trim($_POST['duong_dan_slug'] ?? ''))
            ? trim($_POST['duong_dan_slug'])
            : $this->taoSlug($ten);
        $trangthai = isset($_POST['trang_thai']) ? 1 : 0;

        // Validate rỗng
        if (empty($ten) || empty($slug)) {
            header("Location: ?page=admin-categories&error=empty_fields");
            exit;
        }

        // Validate trùng tên
        if ($this->model->findCategoryByName($ten)) {
            header("Location: ?page=admin-categories&error=duplicate_name");
            exit;
        }

        // Validate trùng slug
        if ($this->model->findCategoryBySlug($slug)) {
            header("Location: ?page=admin-categories&error=duplicate_slug");
            exit;
        }

        // Xử lý upload hình ảnh danh mục
        $hinh_anh = null;
        if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['hinh_anh']['tmp_name'];
            $fileName = $_FILES['hinh_anh']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $newFileName = 'cat_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
            $uploadFileDir = BASE_PATH . '/public/assets/images/categories/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                $hinh_anh = $newFileName;
            }
        }

        $this->model->insertCategory($ten, $slug, $trangthai, $hinh_anh);
        header("Location: ?page=admin-categories&success=created");
        exit;
    }

    public function editCategory(): array
    {
        $this->kiemTraAdmin();
        $id       = intval($_GET['id'] ?? 0);
        $category = $this->model->getCategoryById($id);

        if (!$category) {
            header("Location: ?page=admin-categories&error=not_found");
            exit;
        }

        return [
            'title'    => 'Chỉnh sửa Danh mục | Admin',
            'view'     => 'admin/category/edit.php',
            'category' => $category
        ];
    }

    public function updateCategory()
    {
        $this->kiemTraAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=admin-categories");
            exit;
        }

        $id        = intval($_POST['id'] ?? 0);
        $ten       = trim($_POST['ten_danh_muc'] ?? '');
        $slug      = !empty(trim($_POST['duong_dan_slug'] ?? ''))
            ? trim($_POST['duong_dan_slug'])
            : $this->taoSlug($ten);
        $trangthai = isset($_POST['trang_thai']) ? 1 : 0;

        if ($id <= 0 || empty($ten) || empty($slug)) {
            header("Location: ?page=admin-categories&error=empty_fields");
            exit;
        }

        // Validate trùng tên (bỏ qua bản ghi hiện tại)
        if ($this->model->findCategoryByName($ten, $id)) {
            header("Location: ?page=admin-edit-category&id={$id}&error=duplicate_name");
            exit;
        }

        // Validate trùng slug (bỏ qua bản ghi hiện tại)
        if ($this->model->findCategoryBySlug($slug, $id)) {
            header("Location: ?page=admin-edit-category&id={$id}&error=duplicate_slug");
            exit;
        }

        // Lấy hình ảnh cũ đề phòng không cập nhật ảnh mới
        $category = $this->model->getCategoryById($id);
        $hinh_anh = $category->getHinh_anh() ?? null;

        if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['hinh_anh']['tmp_name'];
            $fileName = $_FILES['hinh_anh']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $newFileName = 'cat_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
            $uploadFileDir = BASE_PATH . '/public/assets/images/categories/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                // Xóa hình cũ
                if (!empty($hinh_anh) && file_exists($uploadFileDir . $hinh_anh)) {
                    @unlink($uploadFileDir . $hinh_anh);
                }
                $hinh_anh = $newFileName;
            }
        }


        $this->model->updateCategory($id, $ten, $slug, $trangthai, $hinh_anh);
        header("Location: ?page=admin-categories&success=updated");
        exit;
    }

    public function deleteCategory()
    {
        $this->kiemTraAdmin();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: ?page=admin-categories&error=invalid_id");
            exit;
        }

        // Chặn xóa nếu còn sản phẩm đang dùng danh mục này
        $soSanPham = $this->model->countProductsByCategory($id);
        if ($soSanPham > 0) {
            header("Location: ?page=admin-categories&error=has_products&count={$soSanPham}");
            exit;
        }

        $this->model->xoaMemCategory($id);
        header("Location: ?page=admin-categories&success=deleted");
        exit;
    }

    // =========================================================
    // THƯƠNG HIỆU
    // =========================================================

    public function brands(): array
    {
        $this->kiemTraAdmin();
        $brands = $this->model->getAllBrands();

        return [
            'title'  => 'Quản lý Thương hiệu | Admin',
            'view'   => 'admin/brand/index.php',
            'brands' => $brands
        ];
    }

    public function storeBrand()
    {
        $this->kiemTraAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=admin-brands");
            exit;
        }

        $ten = trim($_POST['ten_thuong_hieu'] ?? '');
        $mota = trim($_POST['mo_ta'] ?? '');
        $slug = $this->taoSlug($ten);

        if (empty($ten)) {
            header("Location: ?page=admin-brands&error=empty_fields");
            exit;
        }

        // Validate trùng tên
        if ($this->model->findBrandByName($ten)) {
            header("Location: ?page=admin-brands&error=duplicate_name");
            exit;
        }

        // Validate trùng slug
        if ($this->model->findBrandBySlug($slug)) {
            header("Location: ?page=admin-brands&error=duplicate_slug");
            exit;
        }

        // Upload logo
        $anh_logo = null;
        if (isset($_FILES['anh_logo']) && $_FILES['anh_logo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['anh_logo']['tmp_name'];
            $fileName = $_FILES['anh_logo']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $newFileName = 'brand_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
            $uploadFileDir = BASE_PATH . '/public/assets/images/brands/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                $anh_logo = $newFileName;
            }
        }

        $this->model->insertBrand($ten, $slug, $anh_logo, $mota);
        header("Location: ?page=admin-brands&success=created");
        exit;
    }

    public function editBrand(): array
    {
        $this->kiemTraAdmin();
        $id = intval($_GET['id'] ?? 0);
        $brand = $this->model->getBrandById($id);

        if (!$brand) {
            header("Location: ?page=admin-brands&error=not_found");
            exit;
        }

        return [
            'title' => 'Chỉnh sửa Thương hiệu | Admin',
            'view' => 'admin/brand/Edit.php',
            'brand' => $brand
        ];
    }

    public function updateBrand()
    {
        $this->kiemTraAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=admin-brands");
            exit;
        }

        $id = intval($_POST['id'] ?? 0);
        $ten = trim($_POST['ten_thuong_hieu'] ?? '');
        $slug = !empty(trim($_POST['duong_dan_slug'] ?? ''))
            ? trim($_POST['duong_dan_slug'])
            : $this->taoSlug($ten);
        $mota = trim($_POST['mo_ta'] ?? '');
        $trangthai = isset($_POST['trang_thai']) ? 1 : 0;

        if ($id <= 0 || empty($ten) || empty($slug)) {
            header("Location: ?page=admin-brands&error=empty_fields");
            exit;
        }

        // Validate trùng tên (bỏ qua bản ghi hiện tại)
        if ($this->model->findBrandByName($ten, $id)) {
            header("Location: ?page=admin-brand-edit&id={$id}&error=duplicate_name");
            exit;
        }

        // Validate trùng slug (bỏ qua bản ghi hiện tại)
        if ($this->model->findBrandBySlug($slug, $id)) {
            header("Location: ?page=admin-brand-edit&id={$id}&error=duplicate_slug");
            exit;
        }

        // Lấy logo cũ
        $brand = $this->model->getBrandById($id);
        $anh_logo = $brand['anh_logo'] ?? null;

        if (isset($_FILES['anh_logo']) && $_FILES['anh_logo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['anh_logo']['tmp_name'];
            $fileName = $_FILES['anh_logo']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $newFileName = 'brand_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
            $uploadFileDir = BASE_PATH . '/public/assets/images/brands/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                // Xóa logo cũ
                if (!empty($anh_logo) && file_exists($uploadFileDir . $anh_logo)) {
                    @unlink($uploadFileDir . $anh_logo);
                }
                $anh_logo = $newFileName;
            }
        }

        $this->model->updateBrand($id, $ten, $slug, $anh_logo, $mota, $trangthai);
        header("Location: ?page=admin-brands&success=updated");
        exit;
    }

    public function deleteBrand()
    {
        $this->kiemTraAdmin();
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: ?page=admin-brands&error=invalid_id");
            exit;
        }

        // Chặn xóa nếu còn sản phẩm đang dùng thương hiệu này
        $soSanPham = $this->model->countProductsByBrand($id);
        if ($soSanPham > 0) {
            header("Location: ?page=admin-brands&error=has_products&count={$soSanPham}");
            exit;
        }

        $this->model->xoaMemBrand($id);
        header("Location: ?page=admin-brands&success=deleted");
        exit;
    }
}
