<?php

namespace app\controllers\admin;

use app\models\admin\AdminReviewModel;
use app\models\SanPhamModel;

class AdminReviewController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminReviewModel();
    }

    private function kiemTraAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'quan_tri') {
            header("Location: ?page=login");
            exit;
        }
    }

    public function index(): array
    {
        $this->kiemTraAdmin();

        $star = $_GET['star'] ?? '';
        $status = $_GET['status'] ?? '';
        $keyword = $_GET['keyword'] ?? '';

        $reviews = $this->model->getAllReviews($star, $status, $keyword);

        $error = $_SESSION['review_error'] ?? '';
        $success = $_SESSION['review_success'] ?? '';
        unset($_SESSION['review_error'], $_SESSION['review_success']);

        return [
            'title' => 'Quản lý Đánh giá | Admin',
            'view' => 'admin/review/Index.php',
            'reviews' => $reviews,
            'star' => $star,
            'status' => $status,
            'keyword' => $keyword,
            'error' => $error,
            'success' => $success
        ];
    }

    public function toggleStatus(): void
    {
        $this->kiemTraAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $this->model->toggleReviewStatus($id);
            $_SESSION['review_success'] = 'Thay đổi trạng thái hiển thị đánh giá thành công.';
        } else {
            $_SESSION['review_error'] = 'ID đánh giá không hợp lệ.';
        }

        header("Location: ?page=admin-reviews");
        exit;
    }

    public function delete(): void
    {
        $this->kiemTraAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $this->model->deleteReview($id);
            $_SESSION['review_success'] = 'Xóa đánh giá thành công.';
        } else {
            $_SESSION['review_error'] = 'ID đánh giá không hợp lệ.';
        }

        header("Location: ?page=admin-reviews");
        exit;
    }

    public function edit(): ?array
    {
        $this->kiemTraAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $review = $this->model->getReviewById($id);

        if (!$review) {
            $_SESSION['review_error'] = 'Đánh giá không tồn tại.';
            header("Location: ?page=admin-reviews");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $diemSo = isset($_POST['diem_so']) ? (int)$_POST['diem_so'] : 5;
            $binhLuan = isset($_POST['binh_luan']) ? trim($_POST['binh_luan']) : '';
            $trangThai = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : 1;

            if ($diemSo < 1 || $diemSo > 5) {
                $diemSo = 5;
            }

            $res = $this->model->updateReview($id, $diemSo, $binhLuan, $trangThai);
            if ($res) {
                $_SESSION['review_success'] = 'Cập nhật đánh giá thành công.';
                header("Location: ?page=admin-reviews");
                exit;
            } else {
                $error = 'Không thể cập nhật đánh giá.';
            }
        }

        return [
            'title' => 'Chỉnh sửa đánh giá #' . $id . ' | Admin',
            'view' => 'admin/review/Form.php',
            'review' => $review,
            'isEdit' => true,
            'error' => $error ?? ''
        ];
    }

    public function create(): ?array
    {
        $this->kiemTraAdmin();

        // Lấy danh sách sản phẩm để chọn
        $sanPhamModel = new SanPhamModel();
        // Để đơn giản, ta viết một query nhanh trong controller lấy id, ten_san_pham
        $db = new class extends \app\core\Model {
            public function getProductsList() {
                return $this->conn->query("SELECT id, ten_san_pham FROM san_pham WHERE trang_thai = 1 ORDER BY ten_san_pham ASC")->fetchAll(\PDO::FETCH_ASSOC);
            }
            public function getUsersList() {
                return $this->conn->query("SELECT id, ho_ten, email FROM nguoi_dung ORDER BY ho_ten ASC")->fetchAll(\PDO::FETCH_ASSOC);
            }
        };
        $products = $db->getProductsList();
        $users = $db->getUsersList();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = isset($_POST['ma_nguoi_dung']) ? (int)$_POST['ma_nguoi_dung'] : 0;
            $productId = isset($_POST['ma_san_pham']) ? (int)$_POST['ma_san_pham'] : 0;
            $diemSo = isset($_POST['diem_so']) ? (int)$_POST['diem_so'] : 5;
            $binhLuan = isset($_POST['binh_luan']) ? trim($_POST['binh_luan']) : '';

            if ($userId <= 0 || $productId <= 0) {
                $error = 'Vui lòng chọn khách hàng và sản phẩm.';
            } else {
                $res = $this->model->createReview($userId, $productId, $diemSo, $binhLuan);
                if ($res) {
                    $_SESSION['review_success'] = 'Tạo đánh giá thủ công thành công.';
                    header("Location: ?page=admin-reviews");
                    exit;
                } else {
                    $error = 'Không thể tạo đánh giá.';
                }
            }
        }

        return [
            'title' => 'Thêm đánh giá mới | Admin',
            'view' => 'admin/review/Form.php',
            'products' => $products,
            'users' => $users,
            'isEdit' => false,
            'error' => $error ?? ''
        ];
    }
}
