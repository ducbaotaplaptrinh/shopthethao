<?php

namespace app\services;

require_once BASE_PATH . '/app/services/PHPMailer/Exception.php';
require_once BASE_PATH . '/app/services/PHPMailer/PHPMailer.php';
require_once BASE_PATH . '/app/services/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use app\models\entities\DonHang;

class MailService
{
    public static function sendOTP(string $toEmail, string $recipientName, string $otpCode): bool
    {

        $_SESSION['last_sent_otp'] = $otpCode;

        // 2. Gửi mail qua PHPMailer SMTP
        $mail = new PHPMailer(true);

        try {
            // Cấu hình Server gửi mail
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                 // Bật nếu muốn xem log debug chi tiết
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';                     // Máy chủ SMTP của Gmail
            $mail->SMTPAuth   = true;                                 // Bật xác thực SMTP
            $mail->Username   = 'nbao33446@gmail.com';               // Email gửi tin (Hãy thay bằng Email của bạn)
            $mail->Password   = 'mqma nont tgvq fvmp';                  // Mật khẩu ứng dụng Gmail (16 chữ số)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Mã hóa STARTTLS
            $mail->Port       = 587;                                  // Cổng TCP kết nối
            $mail->CharSet    = 'UTF-8';

            // Người nhận & Người gửi
            $mail->setFrom('nbao33446@gmail.com', 'Bảo Đạt Sport');
            $mail->addAddress($toEmail, $recipientName);

            // Nội dung thư
            $mail->isHTML(true);
            $mail->Subject = 'Mã xác thực đăng ký tài khoản - Bảo Đạt Sport';

            $mail->Body = "
            <html>
            <head>
                <title>Mã xác thực OTP</title>
                <style>
                    body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
                    .header { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white; padding: 20px; text-align: center; border-radius: 12px 12px 0 0; }
                    .content { padding: 30px; background-color: #f8f9fa; border-radius: 0 0 12px 12px; }
                    .otp-code { font-size: 32px; font-weight: 800; color: #0d6efd; letter-spacing: 6px; text-align: center; margin: 30px 0; padding: 15px; background: white; border: 2px dashed #0d6efd; border-radius: 8px; }
                    .footer { font-size: 12px; color: #888; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2 style='margin:0; font-size: 24px; font-weight: 700;'>Bảo Đạt Sport</h2>
                    </div>
                    <div class='content'>
                        <p>Xin chào <strong>" . htmlspecialchars($recipientName) . "</strong>,</p>
                        <p>Cảm ơn bạn đã đăng ký tài khoản tại Bảo Đạt Sport. Dưới đây là mã xác thực OTP của bạn để hoàn tất quá trình đăng ký:</p>
                        <div class='otp-code'>" . htmlspecialchars($otpCode) . "</div>
                        <p>Mã này có hiệu lực trong vòng <strong>5 phút</strong>. Vui lòng không chia sẻ mã này với bất kỳ ai để đảm bảo bảo mật thông tin.</p>
                    </div>
                    <div class='footer'>
                        Đây là email gửi tự động từ hệ thống Bảo Đạt Sport. Vui lòng không phản hồi email này.<br>
                        &copy; " . date('Y') . " Bảo Đạt Sport. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Ghi nhận lỗi
            var_dump("Đã có lỗi khi gửi mail: " . $e->getMessage());
            return false;
        }
    }

    public static function sendOrderInvoice(string $toEmail, string $recipientName, DonHang $order, array $items): bool
    {
        // 2. Gửi mail qua PHPMailer SMTP
        $mail = new PHPMailer(true);

        try {
            // Cấu hình Server gửi mail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';                     // Máy chủ SMTP của Gmail
            $mail->SMTPAuth   = true;                                 // Bật xác thực SMTP
            $mail->Username   = 'nbao33446@gmail.com';               // Email gửi tin
            $mail->Password   = 'mqma nont tgvq fvmp';                  // Mật khẩu ứng dụng Gmail (16 chữ số)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Mã hóa STARTTLS
            $mail->Port       = 587;                                  // Cổng TCP kết nối
            $mail->CharSet    = 'UTF-8';

            // Người nhận & Người gửi
            $mail->setFrom('nbao33446@gmail.com', 'Bảo Đạt Sport');
            $mail->addAddress($toEmail, $recipientName);

            // Nội dung thư
            $mail->isHTML(true);
            $mail->Subject = 'Hóa đơn mua hàng #' . $order->getMa_don_hang() . ' - Bảo Đạt Sport';

            // Tạo danh sách sản phẩm dạng HTML
            $itemsHtml = '';
            foreach ($items as $item) {
                $info = $item->getThong_tin_bien_the() ? ' (' . $item->getThong_tin_bien_the() . ')' : '';
                $itemsHtml .= "
                <tr>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: left;'>" . htmlspecialchars($item->getTen_san_pham()) . htmlspecialchars($info) . "</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: center;'>" . $item->getSo_luong() . "</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>" . number_format($item->getGia_mua(), 0, ',', '.') . " ₫</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>" . number_format($item->getThanh_tien(), 0, ',', '.') . " ₫</td>
                </tr>";
            }

            $paymentMethodText = $order->getPhuong_thuc_thanh_toan() === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản qua ngân hàng';

            $mail->Body = "
            <html>
            <head>
                <title>Hóa đơn mua hàng Bảo Đạt Sport</title>
                <style>
                    body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 650px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 12px; }
                    .header { background: linear-gradient(135deg, #ff7b00 0%, #ff9500 100%); color: white; padding: 25px; text-align: center; border-radius: 12px 12px 0 0; }
                    .content { padding: 30px; background-color: #fcfcfc; border-radius: 0 0 12px 12px; }
                    .invoice-info { margin-bottom: 25px; font-size: 14px; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; }
                    .section-title { font-size: 16px; font-weight: bold; color: #ff7b00; margin-bottom: 10px; border-left: 4px solid #ff7b00; padding-left: 8px; }
                    .info-block { background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
                    .table-products { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
                    .table-products th { background-color: #f0f0f0; padding: 10px; font-weight: bold; border-bottom: 2px solid #ddd; }
                    .summary-block { width: 100%; margin-top: 20px; font-size: 14px; text-align: right; }
                    .summary-block td { padding: 5px 0; }
                    .total-row { font-size: 18px; font-weight: bold; color: #d9534f; }
                    .footer { font-size: 12px; color: #888; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2 style='margin:0; font-size: 24px; font-weight: 700;'>CẢM ƠN BẠN ĐÃ ĐẶT HÀNG!</h2>
                        <p style='margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;'>Đơn hàng #" . $order->getMa_don_hang() . " đã được tiếp nhận</p>
                    </div>
                    <div class='content'>
                        <div class='invoice-info'>
                            <table style='width: 100%; font-size: 14px;'>
                                <tr>
                                    <td>
                                        <strong>Mã đơn hàng:</strong> " . $order->getMa_don_hang() . "<br>
                                        <strong>Ngày đặt:</strong> " . ($order->getNgay_tao() ? $order->getNgay_tao()->format('d/m/Y H:i') : date('d/m/Y H:i')) . "
                                    </td>
                                    <td style='text-align: right; vertical-align: top;'>
                                        <strong>Phương thức:</strong> " . $paymentMethodText . "
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class='section-title'>Thông tin giao hàng</div>
                        <div class='info-block'>
                            <strong>Họ và tên người nhận:</strong> " . htmlspecialchars($order->getHo_ten_nguoi_nhan()) . "<br>
                            <strong>Số điện thoại:</strong> " . htmlspecialchars($order->getSo_dien_thoai()) . "<br>
                            <strong>Địa chỉ giao hàng:</strong> " . htmlspecialchars($order->getDia_chi_giao_hang()) . "<br>" .
                ($order->getGhi_chu() ? "<strong>Ghi chú:</strong> " . htmlspecialchars($order->getGhi_chu()) . "<br>" : "") . "
                        </div>

                        <div class='section-title'>Chi tiết sản phẩm</div>
                        <table class='table-products'>
                            <thead>
                                <tr>
                                    <th style='text-align: left;'>Sản phẩm</th>
                                    <th style='width: 10%; text-align: center;'>SL</th>
                                    <th style='width: 25%; text-align: right;'>Đơn giá</th>
                                    <th style='width: 25%; text-align: right;'>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                $itemsHtml
                            </tbody>
                        </table>

                        <table class='summary-block'>
                            <tr>
                                <td style='width: 70%;'><strong>Tạm tính:</strong></td>
                                <td style='width: 30%; font-weight: 600;'>" . number_format($order->getTong_tien_hang(), 0, ',', '.') . " ₫</td>
                            </tr>
                            <tr>
                                <td><strong>Phí giao hàng:</strong></td>
                                <td style='color: green; font-weight: 600;'>Miễn phí</td>
                            </tr>" .
                ($order->getTien_giam_gia() > 0 ? "
                            <tr>
                                <td><strong>Giảm giá:</strong></td>
                                <td style='color: red; font-weight: 600;'>-" . number_format($order->getTien_giam_gia(), 0, ',', '.') . " ₫</td>
                            </tr>" : "") . "
                            <tr class='total-row'>
                                <td>Tổng thanh toán:</td>
                                <td>" . number_format($order->getTong_thanh_toan(), 0, ',', '.') . " ₫</td>
                            </tr>
                        </table>
                    </div>
                    <div class='footer'>
                        Mọi thắc mắc về đơn hàng, vui lòng liên hệ hotline: <strong>0901 234 567</strong> hoặc phản hồi trực tiếp email này.<br>
                        &copy; " . date('Y') . " Bảo Đạt Sport. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            var_dump("Đã có lỗi khi gửi mail: " . $e->getMessage());
            return false;
        }
    }

    public static function sendConsultation(string $fullName, string $phone, string $email, string $content): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình Server gửi mail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';                     // Máy chủ SMTP của Gmail
            $mail->SMTPAuth   = true;                                 // Bật xác thực SMTP
            $mail->Username   = 'nbao33446@gmail.com';               // Email gửi tin
            $mail->Password   = 'mqma nont tgvq fvmp';                  // Mật khẩu ứng dụng Gmail (16 chữ số)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Mã hóa STARTTLS
            $mail->Port       = 587;                                  // Cổng TCP kết nối
            $mail->CharSet    = 'UTF-8';

            // Người nhận & Người gửi
            $mail->setFrom('nbao33446@gmail.com', 'Bảo Đạt Sport');
            $mail->addAddress('nbao33446@gmail.com', 'Ban Quản Trị'); // Gửi tới email của admin/shop
            $mail->addReplyTo($email, $fullName); // Cho phép admin trả lời lại trực tiếp tới email khách hàng

            // Nội dung thư
            $mail->isHTML(true);
            $mail->Subject = 'Yêu cầu tư vấn mới từ khách hàng - ' . $fullName;

            $mail->Body = "
            <html>
            <head>
                <title>Yêu cầu tư vấn mới</title>
                <style>
                    body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
                    .header { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white; padding: 20px; text-align: center; border-radius: 12px 12px 0 0; }
                    .content { padding: 30px; background-color: #f8f9fa; border-radius: 0 0 12px 12px; }
                    .info-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    .info-table td { padding: 10px; border-bottom: 1px solid #eee; font-size: 14px; }
                    .info-table td.label { font-weight: bold; width: 30%; color: #555; }
                    .footer { font-size: 12px; color: #888; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2 style='margin:0; font-size: 24px; font-weight: 700;'>Yêu Cầu Tư Vấn Mới</h2>
                    </div>
                    <div class='content'>
                        <p>Bạn nhận được một yêu cầu tư vấn mới từ khách hàng với thông tin chi tiết dưới đây:</p>
                        <table class='info-table'>
                            <tr>
                                <td class='label'>Họ và tên:</td>
                                <td>" . htmlspecialchars($fullName) . "</td>
                            </tr>
                            <tr>
                                <td class='label'>Số điện thoại:</td>
                                <td>" . htmlspecialchars($phone) . "</td>
                            </tr>
                            <tr>
                                <td class='label'>Email:</td>
                                <td><a href='mailto:" . urlencode($email) . "'>" . htmlspecialchars($email) . "</a></td>
                            </tr>
                            <tr>
                                <td class='label'>Nội dung yêu cầu:</td>
                                <td>" . nl2br(htmlspecialchars($content)) . "</td>
                            </tr>
                        </table>
                    </div>
                    <div class='footer'>
                        Đây là email tự động từ hệ thống Bảo Đạt Sport.<br>
                        &copy; " . date('Y') . " Bảo Đạt Sport. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            var_dump("Đã có lỗi khi gửi mail tư vấn: " . $e->getMessage());
            return false;
        }
    }
}

