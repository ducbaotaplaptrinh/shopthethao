<?php

namespace app\controllers;

use app\models\SanPhamModel;
use app\models\DanhMucModel;
use app\models\BannerModel;
use app\services\MailService;

class HomeController
{
    private $sanPhamModel;
    private $danhMucModel;
    private $bannerModel;

    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
        $this->danhMucModel = new DanhMucModel();
        $this->bannerModel = new BannerModel();
    }
    public function index(): array
    {
        $banners = $this->bannerModel->getActiveBanners('slide_chinh');
        $sanPhamSale = $this->sanPhamModel->getSanPhamSale();
        $sanPhamMoi = $this->sanPhamModel->getSanPhamMoi();
        $danhMucSanPham = $this->danhMucModel->getDanhSachDanhMuc();

        $error = '';
        $success = '';
        $ho_ten = '';
        $so_dien_thoai = '';
        $email = '';
        $noi_dung = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $noi_dung = trim($_POST['noi_dung'] ?? '');

            if (empty($ho_ten) || empty($so_dien_thoai) || empty($email)) {
                $error = 'Vui lòng nhập đầy đủ các thông tin bắt buộc (Họ tên, Số điện thoại, Email)!';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Địa chỉ email không hợp lệ!';
            } else {
                $sent = MailService::sendConsultation($ho_ten, $so_dien_thoai, $email, $noi_dung);
                if ($sent) {
                    $success = 'Yêu cầu tư vấn của bạn đã được gửi thành công! Chúng tôi sẽ liên hệ lại sớm nhất.';
                    // Clear form fields on success
                    $ho_ten = '';
                    $so_dien_thoai = '';
                    $email = '';
                    $noi_dung = '';
                } else {
                    $error = 'Đã xảy ra lỗi trong quá trình gửi mail. Vui lòng thử lại sau!';
                }
            }
        }

        return [
            'title' => "Trang chủ | Bảo Đạt Sport",
            'banners' => $banners,
            'sanPhamSale' => $sanPhamSale,
            'sanPhamMoi' => $sanPhamMoi,
            'danhSachDanhMuc' =>  $danhMucSanPham,
            'error' => $error,
            'success' => $success,
            'ho_ten' => $ho_ten,
            'so_dien_thoai' => $so_dien_thoai,
            'email' => $email,
            'noi_dung' => $noi_dung,
        ];
    }
}
