<?php

namespace app\controllers;

use app\models\NguoiDungModel;
use app\models\HangThanhVienModel;
use app\models\entities\NguoiDung;
use app\services\MailService;

class AuthController
{
    private $nguoiDungModel;
    private $hangThanhVienModel;

    public function __construct()
    {
        $this->nguoiDungModel = new NguoiDungModel();
        $this->hangThanhVienModel = new HangThanhVienModel();
    }

    public function login(): ?array
    {

        if (isset($_SESSION['user']) && $_SESSION['user']['vai_tro'] === "khach_hang") {
            header("Location: ?page=home");
            exit;
        }
        if (isset($_SESSION['user']) && $_SESSION['user']['vai_tro'] === "quan_tri") {
            header("Location: ?page=admin-dashboard");
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
                    //lay ra hang thanh vien 
                    $rankInfo = $this->hangThanhVienModel->getHangThanhVien($user->getId());

                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'ho_ten' => $user->getHo_ten(),
                        'email' => $user->getEmail(),
                        'so_dien_thoai' => $user->getSo_dien_thoai(),
                        'vai_tro' => $user->getVai_tro(),
                        'hang_khach_hang' => [
                            'ten_hang' => $rankInfo['ten_hang'] ?? 'Đồng',
                            'mau_sac' => $rankInfo['mau_sac'] ?? '#cd7f32',
                            'bieu_tuong' => $rankInfo['bieu_tuong'] ?? 'bi-star-half'
                        ]
                    ];

                    // Chuyển hướng phù hợp với vai trò
                    if ($user->getVai_tro() === 'quan_tri') {
                        // Admin luôn được chuyển vào trang quản trị
                        header("Location: ?page=admin-dashboard");
                    } elseif ($redirect === 'checkout') {
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
                    // Thay vì lưu người dùng ngay, lưu dữ liệu tạm và gửi OTP
                    $_SESSION['temp_register_user'] = [
                        'fullname' => $fullname,
                        'email'    => $email,
                        'phone'    => $phone,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'redirect' => $redirect
                    ];

                    $otpCode = (string)rand(100000, 999999);
                    $_SESSION['register_otp'] = [
                        'code'       => $otpCode,
                        'expires_at' => time() + 300 // Hạn 5 phút
                    ];

                    // Gửi email OTP
                    MailService::sendOTP($email, $fullname, $otpCode);

                    header("Location: ?page=verify-otp");
                    exit;
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

    public function verifyOtp(): ?array
    {
        if (isset($_SESSION['user'])) {
            header("Location: ?page=home");
            exit;
        }

        if (!isset($_SESSION['temp_register_user']) || !isset($_SESSION['register_otp'])) {
            header("Location: ?page=register");
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = isset($_POST['action']) ? $_POST['action'] : '';

            if ($action === 'resend') {
                // Gửi lại OTP
                $tempUser = $_SESSION['temp_register_user'];
                $otpCode = (string)rand(100000, 999999);
                $_SESSION['register_otp'] = [
                    'code'       => $otpCode,
                    'expires_at' => time() + 300
                ];

                MailService::sendOTP($tempUser['email'], $tempUser['fullname'], $otpCode);
                $success = 'Mã OTP mới đã được gửi lại vào email của bạn!';
            } else {
                // Xác thực OTP
                $otpInput = isset($_POST['otp']) ? trim($_POST['otp']) : '';

                if (empty($otpInput)) {
                    $error = 'Vui lòng nhập mã OTP!';
                } elseif (time() > $_SESSION['register_otp']['expires_at']) {
                    $error = 'Mã OTP đã hết hạn! Vui lòng nhấn Gửi lại mã.';
                } elseif ($otpInput !== $_SESSION['register_otp']['code']) {
                    $error = 'Mã OTP không chính xác!';
                } else {
                    // Mã OTP chính xác -> Tạo người dùng mới trong DB
                    $tempUser = $_SESSION['temp_register_user'];

                    $userEntity = new NguoiDung();
                    $userEntity->setHo_ten($tempUser['fullname']);
                    $userEntity->setEmail($tempUser['email']);
                    $userEntity->setSo_dien_thoai($tempUser['phone']);
                    $userEntity->setMat_khau($tempUser['password']);
                    $userEntity->setVai_tro('khach_hang');
                    $userEntity->setTrang_thai(true);

                    $userId = $this->nguoiDungModel->createUser($userEntity);
                    if ($userId > 0) {
                        // Tự động phân hạng Đồng (hoặc hạng thấp nhất) cho người mới
                        $sqlDefaultRank = "SELECT id, ten_hang, mau_sac, bieu_tuong FROM hang_thanh_vien ORDER BY muc_chi_tieu_toi_thieu ASC LIMIT 1";
                        $stmtDefaultRank = (new \PDO("mysql:host=localhost;dbname=bd_baodatsport", "root", ""))->query($sqlDefaultRank);
                        $defaultRank = $stmtDefaultRank->fetch(\PDO::FETCH_ASSOC);

                        if ($defaultRank) {
                            $sqlUpdateUserRank = "UPDATE nguoi_dung SET ma_hang = :ma_hang WHERE id = :uid";
                            $stmtUpdateUserRank = (new \PDO("mysql:host=localhost;dbname=bd_baodatsport", "root", ""))->prepare($sqlUpdateUserRank);
                            $stmtUpdateUserRank->execute(['ma_hang' => $defaultRank['id'], 'uid' => $userId]);
                        }

                        // Đăng nhập tự động
                        $_SESSION['user'] = [
                            'id'           => $userId,
                            'ho_ten'       => $tempUser['fullname'],
                            'email'        => $tempUser['email'],
                            'so_dien_thoai' => $tempUser['phone'],
                            'vai_tro'      => 'khach_hang',
                            'hang_khach_hang' => [
                                'ten_hang' => $defaultRank['ten_hang'] ?? 'Đồng',
                                'mau_sac' => $defaultRank['mau_sac'] ?? '#cd7f32',
                                'bieu_tuong' => $defaultRank['bieu_tuong'] ?? 'bi-star-half'
                            ]
                        ];

                        // Xóa dữ liệu tạm
                        unset($_SESSION['temp_register_user']);
                        unset($_SESSION['register_otp']);
                        unset($_SESSION['last_sent_otp']);

                        $redirect = $tempUser['redirect'] ?? '';
                        if ($redirect === 'checkout') {
                            header("Location: ?page=checkout");
                        } else {
                            header("Location: ?page=home");
                        }
                        exit;
                    } else {
                        $error = 'Có lỗi xảy ra khi tạo tài khoản, vui lòng thử lại!';
                    }
                }
            }
        }

        return [
            'title' => 'Xác thực OTP | Bảo Đạt Sport',
            'error' => $error,
            'success' => $success
        ];
    }

    public function changePassword(): ?array
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login");
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
            $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            $confirmNewPassword = isset($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';

            if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
                $error = 'Vui lòng nhập đầy đủ tất cả các trường!';
            } elseif ($newPassword !== $confirmNewPassword) {
                $error = 'Mật khẩu mới không trùng khớp!';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
            } else {
                $userId = $_SESSION['user']['id'];
                // Lấy thông tin người dùng từ DB để verify mật khẩu cũ
                $user = $this->nguoiDungModel->getUserById($userId);

                if ($user && password_verify($currentPassword, $user->getMat_khau())) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $result = $this->nguoiDungModel->updatePassword($userId, $hashedPassword);
                    if ($result) {
                        $success = 'Đổi mật khẩu thành công!';
                    } else {
                        $error = 'Cập nhật mật khẩu thất bại, vui lòng thử lại!';
                    }
                } else {
                    $error = 'Mật khẩu hiện tại không chính xác!';
                }
            }
        }

        return [
            'title' => 'Đổi mật khẩu | Bảo Đạt Sport',
            'error' => $error,
            'success' => $success
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
