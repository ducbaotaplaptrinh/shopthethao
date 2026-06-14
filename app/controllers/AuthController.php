<?php

namespace app\controllers;

use app\models\NguoiDungModel;
use app\models\entities\NguoiDung;


class AuthController
{
    private $nguoiDungModel;

    public function __construct()
    {
        $this->nguoiDungModel = new NguoiDungModel();
    }

    public function login(): ?array
    {

        if (isset($_SESSION['user'])) {
            header("Location: ?page=home");
            exit;
        }

        $error = '';
        $email = '';
        $redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : '';

            if (empty($email) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ email và mật khẩu!';
            } else {
                $user = $this->nguoiDungModel->getUserByEmail($email);
                if ($user && password_verify($password, $user->getMat_khau())) {

                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'ho_ten' => $user->getHo_ten(),
                        'email' => $user->getEmail(),
                        'so_dien_thoai' => $user->getSo_dien_thoai(),
                        'vai_tro' => $user->getVai_tro()
                    ];

                    // Redirect appropriately
                    if ($redirect === 'checkout') {
                        header("Location: ?page=checkout");
                    } else {
                        header("Location: ?page=home");
                    }
                    exit;
                } else {
                    $error = 'Email hoặc mật khẩu không chính xác!';
                }
            }
        }

        return [
            'title' => 'Đăng nhập | Bảo Đạt Sport',
            'error' => $error,
            'email' => $email,
            'redirect' => $redirect
        ];
    }

    public function register(): ?array
    {
        if (isset($_SESSION['user'])) {
            header("Location: ?page=home");
            exit;
        }

        $error = '';
        $fullname = '';
        $email = '';
        $phone = '';
        $redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : '';

            if (empty($fullname) || empty($email) || empty($phone) || empty($password)) {
                $error = 'Vui lòng điền đầy đủ tất cả thông tin!';
            } else {

                // kiểm tra xem mail tồn tại ch
                if ($this->nguoiDungModel->getUserByEmail($email)) {
                    $error = 'Địa chỉ email này đã được sử dụng!';
                }
                // kiểm tra xem điện thoại tồn tại ch
                elseif ($this->nguoiDungModel->getUserByPhone($phone)) {
                    $error = 'Số điện thoại này đã được sử dụng!';
                } else {
                    // Xây dựng thực thể NguoiDung trước khi lưu vào CSDL
                    $userEntity = new NguoiDung();
                    $userEntity->setHo_ten($fullname);
                    $userEntity->setEmail($email);
                    $userEntity->setSo_dien_thoai($phone);
                    // Mã hóa mật khẩu bằng Bcrypt trước khi gán vào thực thể
                    $userEntity->setMat_khau(password_hash($password, PASSWORD_DEFAULT));
                    $userEntity->setVai_tro('khach_hang');
                    $userEntity->setTrang_thai(true);

                    $userId = $this->nguoiDungModel->createUser($userEntity);
                    if ($userId > 0) {
                        // Tự động đăng nhập ngay sau khi đăng ký thành công
                        $_SESSION['user'] = [
                            'id'           => $userId,
                            'ho_ten'       => $fullname,
                            'email'        => $email,
                            'so_dien_thoai' => $phone,
                            'vai_tro'      => 'khach_hang'
                        ];

                        if ($redirect === 'checkout') {
                            header("Location: ?page=checkout");
                        } else {
                            header("Location: ?page=home");
                        }
                        exit;
                    } else {
                        $error = 'Có lỗi xảy ra trong quá trình đăng ký, vui lòng thử lại!';
                    }
                }
            }
        }

        return [
            'title' => 'Đăng ký | Bảo Đạt Sport',
            'error' => $error,
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'redirect' => $redirect
        ];
    }

    public function logout(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        header("Location: ?page=home");
        exit;
    }
}
