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
                    if (!$user->getTrang_thai()) {
                        $error = 'Tài khoản của bạn đã bị khóa hoặc tạm ngưng hoạt động!';
                    } else {
                        //lay ra hang thanh vien 
                        $rankInfo = $this->hangThanhVienModel->getHangThanhVien($user->getId());

                        $_SESSION['user'] = [
                            'id' => $user->getId(),
                            'ho_ten' => $user->getHo_ten(),
                            'email' => $user->getEmail(),
                            'so_dien_thoai' => $user->getSo_dien_thoai(),
                            'vai_tro' => $user->getVai_tro(),
                            'anh_dai_dien' => $user->getAnh_dai_dien(),
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
                    }
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
                        $stmtDefaultRank = $this->nguoiDungModel->conn->query($sqlDefaultRank);
                        $defaultRank = $stmtDefaultRank->fetch(\PDO::FETCH_ASSOC);

                        if ($defaultRank) {
                            $sqlUpdateUserRank = "UPDATE nguoi_dung SET ma_hang = :ma_hang WHERE id = :uid";
                            $stmtUpdateUserRank = $this->nguoiDungModel->conn->prepare($sqlUpdateUserRank);
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
        $error = '';
        $success = '';
        $step = 1;
        $email = '';

        // Case A: User is logged in (normal change password)
        if (isset($_SESSION['user'])) {
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
                'success' => $success,
                'is_logged_in' => true
            ];
        }

        // Case B: User is not logged in (Forgot Password via OTP)
        if (!isset($_SESSION['forgot_password_step'])) {
            $_SESSION['forgot_password_step'] = 1;
        }

        $step = $_SESSION['forgot_password_step'];
        $error = $_SESSION['forgot_password_error'] ?? '';
        $success = $_SESSION['forgot_password_success'] ?? '';
        unset($_SESSION['forgot_password_error'], $_SESSION['forgot_password_success']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = isset($_POST['action']) ? $_POST['action'] : '';

            if ($step == 1) {
                $email = isset($_POST['email']) ? trim($_POST['email']) : '';
                if (empty($email)) {
                    $_SESSION['forgot_password_error'] = 'Vui lòng nhập địa chỉ email!';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['forgot_password_error'] = 'Định dạng email không hợp lệ!';
                } else {
                    $user = $this->nguoiDungModel->getUserByEmail($email);
                    if (!$user) {
                        $_SESSION['forgot_password_error'] = 'Email này không tồn tại trong hệ thống!';
                    } else {
                        $otpCode = (string)rand(100000, 999999);
                        $_SESSION['forgot_password_email'] = $email;
                        $_SESSION['forgot_password_otp'] = [
                            'code' => $otpCode,
                            'expires_at' => time() + 300 // Hạn 5 phút
                        ];
                        $_SESSION['forgot_password_fullname'] = $user->getHo_ten();

                        if (MailService::sendOTP($email, $user->getHo_ten(), $otpCode)) {
                            $_SESSION['forgot_password_step'] = 2;
                            $_SESSION['forgot_password_success'] = 'Mã OTP đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư!';
                        } else {
                            $_SESSION['forgot_password_error'] = 'Không thể gửi email OTP, vui lòng thử lại sau!';
                        }
                    }
                }
                header("Location: ?page=change-password");
                exit;
            } elseif ($step == 2) {
                if ($action === 'resend') {
                    $email = $_SESSION['forgot_password_email'] ?? '';
                    $fullname = $_SESSION['forgot_password_fullname'] ?? 'Khách hàng';
                    if (empty($email)) {
                        $_SESSION['forgot_password_error'] = 'Thông tin phiên làm việc không hợp lệ. Vui lòng quay lại bước 1.';
                        $_SESSION['forgot_password_step'] = 1;
                    } else {
                        $otpCode = (string)rand(100000, 999999);
                        $_SESSION['forgot_password_otp'] = [
                            'code' => $otpCode,
                            'expires_at' => time() + 300
                        ];
                        if (MailService::sendOTP($email, $fullname, $otpCode)) {
                            $_SESSION['forgot_password_success'] = 'Mã OTP mới đã được gửi lại vào email của bạn!';
                        } else {
                            $_SESSION['forgot_password_error'] = 'Không thể gửi lại mã OTP, vui lòng thử lại sau!';
                        }
                    }
                } else {
                    $otpInput = isset($_POST['otp']) ? trim($_POST['otp']) : '';
                    $otpSession = $_SESSION['forgot_password_otp'] ?? null;

                    if (empty($otpInput)) {
                        $_SESSION['forgot_password_error'] = 'Vui lòng nhập mã OTP!';
                    } elseif (!$otpSession) {
                        $_SESSION['forgot_password_error'] = 'Không tìm thấy mã OTP. Vui lòng gửi lại mã.';
                    } elseif (time() > $otpSession['expires_at']) {
                        $_SESSION['forgot_password_error'] = 'Mã OTP đã hết hạn! Vui lòng nhấn Gửi lại mã.';
                    } elseif ($otpInput !== $otpSession['code']) {
                        $_SESSION['forgot_password_error'] = 'Mã OTP không chính xác!';
                    } else {
                        $_SESSION['forgot_password_step'] = 3;
                        $_SESSION['forgot_password_verified'] = true;
                    }
                }
                header("Location: ?page=change-password");
                exit;
            } elseif ($step == 3) {
                $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
                $confirmNewPassword = isset($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';
                $email = $_SESSION['forgot_password_email'] ?? '';
                $verified = $_SESSION['forgot_password_verified'] ?? false;

                if (empty($email) || !$verified) {
                    $_SESSION['forgot_password_error'] = 'Yêu cầu không hợp lệ. Vui lòng bắt đầu lại từ bước 1.';
                    $_SESSION['forgot_password_step'] = 1;
                } elseif (empty($newPassword) || empty($confirmNewPassword)) {
                    $_SESSION['forgot_password_error'] = 'Vui lòng điền đầy đủ thông tin mật khẩu mới!';
                } elseif ($newPassword !== $confirmNewPassword) {
                    $_SESSION['forgot_password_error'] = 'Mật khẩu xác nhận không khớp!';
                } elseif (strlen($newPassword) < 6) {
                    $_SESSION['forgot_password_error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
                } else {
                    $user = $this->nguoiDungModel->getUserByEmail($email);
                    if (!$user) {
                        $_SESSION['forgot_password_error'] = 'Tài khoản không tồn tại!';
                    } else {
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        if ($this->nguoiDungModel->updatePassword($user->getId(), $hashedPassword)) {
                            $_SESSION['forgot_password_success'] = 'Đổi mật khẩu thành công!';
                            $_SESSION['forgot_password_step'] = 4;
                            // Clear session variables
                            unset($_SESSION['forgot_password_email']);
                            unset($_SESSION['forgot_password_otp']);
                            unset($_SESSION['forgot_password_fullname']);
                            unset($_SESSION['forgot_password_verified']);
                        } else {
                            $_SESSION['forgot_password_error'] = 'Có lỗi xảy ra trong quá trình cập nhật mật khẩu!';
                        }
                    }
                }
                header("Location: ?page=change-password");
                exit;
            }
        }

        // Clean up step 4 when returning to login page
        if ($step === 4) {
            unset($_SESSION['forgot_password_step']);
        }

        return [
            'title' => 'Khôi phục mật khẩu | Bảo Đạt Sport',
            'error' => $error,
            'success' => $success,
            'is_logged_in' => false,
            'step' => $step,
            'email' => $email
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

    public function profile(): array
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login&redirect=profile");
            exit;
        }

        $userId = (int)$_SESSION['user']['id'];
        $user = $this->nguoiDungModel->getUserById($userId);
        if (!$user) {
            unset($_SESSION['user']);
            header("Location: ?page=login");
            exit;
        }

        $rankInfo = $this->hangThanhVienModel->getHangThanhVien($userId);
        if ($rankInfo) {
            $_SESSION['user']['hang_khach_hang'] = [
                'ten_hang' => $rankInfo['ten_hang'],
                'mau_sac' => $rankInfo['mau_sac'],
                'bieu_tuong' => $rankInfo['bieu_tuong']
            ];
        }

        $error = $_SESSION['profile_error'] ?? '';
        $success = $_SESSION['profile_success'] ?? '';
        unset($_SESSION['profile_error'], $_SESSION['profile_success']);

        $activeTab = $_GET['tab'] ?? 'info'; // info | address

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postAction = $_POST['action'] ?? '';

            if ($postAction === 'update_profile') {
                $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
                $email = isset($_POST['email']) ? trim($_POST['email']) : '';
                $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
                $oldPassword = $_POST['old_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';

                if (empty($fullname) || empty($email) || empty($phone)) {
                    $_SESSION['profile_error'] = 'Vui lòng điền đầy đủ các thông tin bắt buộc!';
                    header("Location: ?page=profile&tab=info");
                    exit;
                }

                // Check email taken by other user
                $checkEmailUser = $this->nguoiDungModel->getUserByEmail($email);
                if ($checkEmailUser && $checkEmailUser->getId() !== $userId) {
                    $_SESSION['profile_error'] = 'Địa chỉ email này đã được sử dụng bởi tài khoản khác!';
                    header("Location: ?page=profile&tab=info");
                    exit;
                }

                // Check phone taken by other user
                $checkPhoneUser = $this->nguoiDungModel->getUserByPhone($phone);
                if ($checkPhoneUser && $checkPhoneUser->getId() !== $userId) {
                    $_SESSION['profile_error'] = 'Số điện thoại này đã được sử dụng bởi tài khoản khác!';
                    header("Location: ?page=profile&tab=info");
                    exit;
                }

                $user->setHo_ten($fullname);
                $user->setEmail($email);
                $user->setSo_dien_thoai($phone);

                // Handle avatar upload
                if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['anh_dai_dien']['tmp_name'];
                    $fileName = $_FILES['anh_dai_dien']['name'];
                    $fileSize = $_FILES['anh_dai_dien']['size'];
                    $fileType = $_FILES['anh_dai_dien']['type'];
                    
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));
                    
                    $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg', 'webp'];
                    if (in_array($fileExtension, $allowedfileExtensions)) {
                        // Max file size 3MB
                        if ($fileSize <= 3 * 1024 * 1024) {
                            $uploadFileDir = BASE_PATH . '/public/assets/images/avatars/';
                            if (!is_dir($uploadFileDir)) {
                                mkdir($uploadFileDir, 0777, true);
                            }
                            $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExtension;
                            $dest_path = $uploadFileDir . $newFileName;
                            
                            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                                // Delete old avatar file if exists and is not default
                                $oldAvatar = $user->getAnh_dai_dien();
                                if ($oldAvatar && $oldAvatar !== 'assets/images/avatars/avt.jpg') {
                                    $oldAvatarPath = BASE_PATH . '/public/' . $oldAvatar;
                                    if (file_exists($oldAvatarPath)) {
                                        @unlink($oldAvatarPath);
                                    }
                                }
                                $user->setAnh_dai_dien('assets/images/avatars/' . $newFileName);
                            } else {
                                $_SESSION['profile_error'] = 'Có lỗi xảy ra khi di chuyển tệp đã tải lên.';
                                header("Location: ?page=profile&tab=info");
                                exit;
                            }
                        } else {
                            $_SESSION['profile_error'] = 'Kích thước ảnh đại diện không được vượt quá 3MB.';
                            header("Location: ?page=profile&tab=info");
                            exit;
                        }
                    } else {
                        $_SESSION['profile_error'] = 'Định dạng ảnh không hợp lệ. Chỉ chấp nhận các định dạng: JPG, JPEG, PNG, GIF, WEBP.';
                        header("Location: ?page=profile&tab=info");
                        exit;
                    }
                }

                // Handle password update
                $updatePassword = false;
                if (!empty($newPassword)) {
                    if (empty($oldPassword)) {
                        $_SESSION['profile_error'] = 'Vui lòng nhập mật khẩu hiện tại để đổi mật khẩu mới!';
                        header("Location: ?page=profile&tab=info");
                        exit;
                    }

                    if (!password_verify($oldPassword, $user->getMat_khau())) {
                        $_SESSION['profile_error'] = 'Mật khẩu hiện tại không chính xác!';
                        header("Location: ?page=profile&tab=info");
                        exit;
                    }

                    $user->setMat_khau(password_hash($newPassword, PASSWORD_DEFAULT));
                    $updatePassword = true;
                }

                if ($this->nguoiDungModel->updateUser($user, $updatePassword)) {
                    $_SESSION['user']['ho_ten'] = $fullname;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['so_dien_thoai'] = $phone;
                    $_SESSION['user']['anh_dai_dien'] = $user->getAnh_dai_dien();
                    $_SESSION['profile_success'] = 'Cập nhật thông tin cá nhân thành công!';
                } else {
                    $_SESSION['profile_error'] = 'Không thể cập nhật thông tin cá nhân. Vui lòng thử lại!';
                }
                header("Location: ?page=profile&tab=info");
                exit;
            }

            if ($postAction === 'add_address') {
                $recipientName = isset($_POST['ho_ten_nguoi_nhan']) ? trim($_POST['ho_ten_nguoi_nhan']) : '';
                $phone = isset($_POST['so_dien_thoai']) ? trim($_POST['so_dien_thoai']) : '';
                $detail = isset($_POST['dia_chi_chi_tiet']) ? trim($_POST['dia_chi_chi_tiet']) : '';
                $ward = isset($_POST['phuong_xa']) ? trim($_POST['phuong_xa']) : '';
                $district = isset($_POST['quan_huyen']) ? trim($_POST['quan_huyen']) : '';
                $province = isset($_POST['tinh_thanh_pho']) ? trim($_POST['tinh_thanh_pho']) : '';
                $isDefault = isset($_POST['la_mac_dinh']) ? 1 : 0;

                if (empty($recipientName) || empty($phone) || empty($detail) || empty($ward) || empty($district) || empty($province)) {
                    $_SESSION['profile_error'] = 'Vui lòng điền đầy đủ các thông tin địa chỉ!';
                    header("Location: ?page=profile&tab=address");
                    exit;
                }

                $addressData = [
                    'ma_nguoi_dung' => $userId,
                    'ho_ten_nguoi_nhan' => $recipientName,
                    'so_dien_thoai' => $phone,
                    'dia_chi_chi_tiet' => $detail,
                    'phuong_xa' => $ward,
                    'quan_huyen' => $district,
                    'tinh_thanh_pho' => $province,
                    'la_mac_dinh' => $isDefault
                ];

                if ($this->nguoiDungModel->addAddress($addressData)) {
                    $_SESSION['profile_success'] = 'Thêm địa chỉ mới thành công!';
                } else {
                    $_SESSION['profile_error'] = 'Không thể thêm địa chỉ. Vui lòng thử lại!';
                }
                header("Location: ?page=profile&tab=address");
                exit;
            }

            if ($postAction === 'edit_address') {
                $addressId = isset($_POST['address_id']) ? (int)$_POST['address_id'] : 0;
                $recipientName = isset($_POST['ho_ten_nguoi_nhan']) ? trim($_POST['ho_ten_nguoi_nhan']) : '';
                $phone = isset($_POST['so_dien_thoai']) ? trim($_POST['so_dien_thoai']) : '';
                $detail = isset($_POST['dia_chi_chi_tiet']) ? trim($_POST['dia_chi_chi_tiet']) : '';
                $ward = isset($_POST['phuong_xa']) ? trim($_POST['phuong_xa']) : '';
                $district = isset($_POST['quan_huyen']) ? trim($_POST['quan_huyen']) : '';
                $province = isset($_POST['tinh_thanh_pho']) ? trim($_POST['tinh_thanh_pho']) : '';
                $isDefault = isset($_POST['la_mac_dinh']) ? 1 : 0;

                if ($addressId <= 0 || empty($recipientName) || empty($phone) || empty($detail) || empty($ward) || empty($district) || empty($province)) {
                    $_SESSION['profile_error'] = 'Vui lòng điền đầy đủ các thông tin địa chỉ!';
                    header("Location: ?page=profile&tab=address");
                    exit;
                }

                $addressData = [
                    'ho_ten_nguoi_nhan' => $recipientName,
                    'so_dien_thoai' => $phone,
                    'dia_chi_chi_tiet' => $detail,
                    'phuong_xa' => $ward,
                    'quan_huyen' => $district,
                    'tinh_thanh_pho' => $province,
                    'la_mac_dinh' => $isDefault
                ];

                if ($this->nguoiDungModel->updateAddress($addressId, $userId, $addressData)) {
                    $_SESSION['profile_success'] = 'Cập nhật địa chỉ thành công!';
                } else {
                    $_SESSION['profile_error'] = 'Không thể cập nhật địa chỉ. Vui lòng thử lại!';
                }
                header("Location: ?page=profile&tab=address");
                exit;
            }

            if ($postAction === 'delete_address') {
                $addressId = isset($_POST['address_id']) ? (int)$_POST['address_id'] : 0;
                if ($addressId > 0) {
                    if ($this->nguoiDungModel->deleteAddress($addressId, $userId)) {
                        $_SESSION['profile_success'] = 'Xóa địa chỉ thành công!';
                    } else {
                        $_SESSION['profile_error'] = 'Không thể xóa địa chỉ. Vui lòng thử lại!';
                    }
                }
                header("Location: ?page=profile&tab=address");
                exit;
            }

            if ($postAction === 'set_default_address') {
                $addressId = isset($_POST['address_id']) ? (int)$_POST['address_id'] : 0;
                if ($addressId > 0) {
                    if ($this->nguoiDungModel->setDefaultAddress($addressId, $userId)) {
                        $_SESSION['profile_success'] = 'Đặt địa chỉ mặc định thành công!';
                    } else {
                        $_SESSION['profile_error'] = 'Không thể đặt mặc định. Vui lòng thử lại!';
                    }
                }
                header("Location: ?page=profile&tab=address");
                exit;
            }
        }

        $addresses = $this->nguoiDungModel->getUserAddresses($userId);

        return [
            'title' => 'Thông tin cá nhân | Bảo Đạt Sport',
            'user' => $user,
            'addresses' => $addresses,
            'error' => $error,
            'success' => $success,
            'activeTab' => $activeTab,
            'rankInfo' => $rankInfo
        ];
    }
}
