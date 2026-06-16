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
            'activeTab' => $activeTab
        ];
    }
}
