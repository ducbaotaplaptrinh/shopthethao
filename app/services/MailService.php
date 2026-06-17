<?php

namespace app\services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once BASE_PATH . '/app/services/PHPMailer/Exception.php';
require_once BASE_PATH . '/app/services/PHPMailer/PHPMailer.php';
require_once BASE_PATH . '/app/services/PHPMailer/SMTP.php';

class MailService
{
    public static function sendOTP(string $toEmail, string $recipientName, string $otpCode): bool
    {
        // 1. Ghi log ra file để test tiện lợi trên localhost đề phòng SMTP cấu hình sai hoặc chưa thay đổi thông tin
        $logDir = BASE_PATH . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . '/email_logs.log';
        $logContent = "[" . date('Y-m-d H:i:s') . "] [PHPMailer Service] Gửi tới: $toEmail | Tên: $recipientName | OTP: $otpCode\r\n";
        file_put_contents($logFile, $logContent, FILE_APPEND);

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
            // Ghi nhận lỗi SMTP cụ thể ra file logs
            file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] SMTP Error: " . $mail->ErrorInfo . "\r\n", FILE_APPEND);
            return false;
        }
    }

    public static function sendOrderInvoice(string $toEmail, string $recipientName, $order, array $cartItems): bool
    {
        $logDir = BASE_PATH . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . '/email_logs.log';
        $logContent = "[" . date('Y-m-d H:i:s') . "] [PHPMailer Service] Gửi hóa đơn tới: $toEmail | Mã đơn: " . $order->getMa_don_hang() . "\r\n";
        file_put_contents($logFile, $logContent, FILE_APPEND);

        if (empty($toEmail)) {
            return false;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'nbao33446@gmail.com';
            $mail->Password   = 'mqma nont tgvq fvmp';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('nbao33446@gmail.com', 'Bảo Đạt Sport');
            $mail->addAddress($toEmail, $recipientName);

            $itemsHtml = '';
            foreach ($cartItems as $index => $item) {
                $imagePath = $item['image'];
                $cleanPath = trim($imagePath);
                if (strpos($cleanPath, 'assets/') !== 0) {
                    $cleanPath = 'assets/images/' . $cleanPath;
                }
                $fullPath = BASE_PATH . '/public/' . $cleanPath;
                
                $cid = 'prod_img_' . $index;
                $hasImage = false;
                if (!empty($imagePath) && file_exists($fullPath)) {
                    $mail->addEmbeddedImage($fullPath, $cid);
                    $hasImage = true;
                }

                $imgSrc = $hasImage ? "cid:$cid" : "https://via.placeholder.com/60?text=No+Image";
                $variationInfo = !empty($item['attributes']) ? '<br><small style="color: #64748b;">Biến thể: ' . htmlspecialchars($item['attributes']) . '</small>' : '';
                
                $itemsHtml .= '
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px 8px; text-align: left; vertical-align: middle;">
                        <img src="' . $imgSrc . '" alt="' . htmlspecialchars($item['name']) . '" style="width: 50px; height: 50px; object-fit: contain; border-radius: 6px; border: 1px solid #f1f5f9; background-color: #f8fafc; padding: 2px;">
                    </td>
                    <td style="padding: 12px 8px; text-align: left; vertical-align: middle;">
                        <span style="font-weight: 600; color: #1e293b; font-size: 14px;">' . htmlspecialchars($item['name']) . '</span>
                        ' . $variationInfo . '
                    </td>
                    <td style="padding: 12px 8px; text-align: center; vertical-align: middle; color: #475569; font-size: 14px;">' . $item['qty'] . '</td>
                    <td style="padding: 12px 8px; text-align: right; vertical-align: middle; color: #1e293b; font-weight: 600; font-size: 14px;">' . formatVND($item['price']) . '</td>
                    <td style="padding: 12px 8px; text-align: right; vertical-align: middle; color: #ff5722; font-weight: 700; font-size: 14px;">' . formatVND($item['price'] * $item['qty']) . '</td>
                </tr>';
            }

            $paymentMethod = $order->getPhuong_thuc_thanh_toan() === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng';
            $paymentStatus = $order->getTrang_thai_thanh_toan() === 'da_thanh_toan' ? '<span style="color: #10b981; font-weight: bold;">Đã thanh toán</span>' : '<span style="color: #f59e0b; font-weight: bold;">Chưa thanh toán</span>';

            $mail->isHTML(true);
            $mail->Subject = 'Xác nhận đơn hàng mới #' . $order->getMa_don_hang() . ' - Bảo Đạt Sport';
            
            $mail->Body = '
            <html>
            <head>
                <meta charset="utf-8">
                <style>
                    body { font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; background-color: #f8fafc; }
                    .wrapper { max-width: 650px; margin: 20px auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #e2e8f0; }
                    .header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 30px; text-align: center; }
                    .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 0.5px; }
                    .header p { margin: 5px 0 0 0; font-size: 14px; opacity: 0.8; }
                    .content { padding: 30px; }
                    .greeting { font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 15px; }
                    .info-grid { display: table; width: 100%; margin-bottom: 25px; border-collapse: collapse; }
                    .info-col { display: table-cell; width: 50%; padding: 15px; background-color: #f1f5f9; border-radius: 8px; border: 4px solid #ffffff; vertical-align: top; }
                    .info-col h3 { margin: 0 0 8px 0; font-size: 13px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }
                    .info-col p { margin: 0; font-size: 14px; color: #1e293b; line-height: 1.5; }
                    .table-title { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 12px; border-left: 4px solid #ff5722; padding-left: 8px; }
                    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
                    .items-table th { background-color: #f8fafc; padding: 10px 8px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
                    .summary-table { width: 100%; max-width: 300px; margin-left: auto; border-collapse: collapse; }
                    .summary-table td { padding: 8px 0; font-size: 14px; color: #475569; }
                    .summary-table .total-row td { font-size: 16px; font-weight: 800; color: #ff5722; border-top: 1px solid #e2e8f0; padding-top: 12px; }
                    .footer { background-color: #f8fafc; padding: 25px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
                    .button-link { display: inline-block; background-color: #0f172a; color: #ffffff !important; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; margin-top: 15px; }
                </style>
            </head>
            <body>
                <div class="wrapper">
                    <div class="header">
                        <h1>BẢO ĐẠT SPORT</h1>
                        <p>Cảm ơn bạn đã tin tưởng mua sắm cùng chúng tôi!</p>
                    </div>
                    <div class="content">
                        <div class="greeting">Xin chào ' . htmlspecialchars($recipientName) . ',</div>
                        <p style="margin-top: 0; font-size: 14px; color: #475569;">
                            Đơn hàng của bạn đã được tiếp nhận thành công và đang chờ xác nhận. Dưới đây là thông tin chi tiết hóa đơn của đơn hàng <strong>#' . $order->getMa_don_hang() . '</strong>:
                        </p>

                        <div class="info-grid">
                            <div class="info-col" style="padding: 15px; background-color: #f1f5f9; border-radius: 8px;">
                                <h3 style="margin: 0 0 8px 0; font-size: 13px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Thông tin nhận hàng</h3>
                                <p style="margin: 0; font-size: 14px; color: #1e293b; line-height: 1.5;">
                                    <strong>' . htmlspecialchars($order->getHo_ten_nguoi_nhan()) . '</strong><br>
                                    SĐT: ' . htmlspecialchars($order->getSo_dien_thoai()) . '<br>
                                    Địa chỉ: ' . htmlspecialchars($order->getDia_chi_giao_hang()) . '
                                </p>
                            </div>
                            <div class="info-col" style="padding: 15px; background-color: #f1f5f9; border-radius: 8px;">
                                <h3 style="margin: 0 0 8px 0; font-size: 13px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Thanh toán & Đơn hàng</h3>
                                <p style="margin: 0; font-size: 14px; color: #1e293b; line-height: 1.5;">
                                    Mã đơn: <strong>' . $order->getMa_don_hang() . '</strong><br>
                                    Hình thức: ' . $paymentMethod . '<br>
                                    Trạng thái: ' . $paymentStatus . '
                                </p>
                            </div>
                        </div>

                        <div class="table-title">Chi tiết sản phẩm</div>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">Ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th style="width: 50px; text-align: center;">SL</th>
                                    <th style="width: 90px; text-align: right;">Đơn giá</th>
                                    <th style="width: 100px; text-align: right;">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . $itemsHtml . '
                            </tbody>
                        </table>

                        <table class="summary-table">
                            <tr>
                                <td>Tạm tính:</td>
                                <td style="text-align: right; font-weight: 600;">' . formatVND($order->getTong_tien_hang()) . '</td>
                            </tr>
                            <tr>
                                <td>Phí vận chuyển:</td>
                                <td style="text-align: right; font-weight: 600;">' . formatVND($order->getPhi_van_chuyen()) . '</td>
                            </tr>
                            ' . ($order->getTien_giam_gia() > 0 ? '
                            <tr>
                                <td>Giảm giá:</td>
                                <td style="text-align: right; font-weight: 600; color: #10b981;">-' . formatVND($order->getTien_giam_gia()) . '</td>
                            </tr>' : '') . '
                            <tr class="total-row">
                                <td>Tổng thanh toán:</td>
                                <td style="text-align: right; font-weight: 800;">' . formatVND($order->getTong_thanh_toan()) . '</td>
                            </tr>
                        </table>

                        <div style="text-align: center; margin-top: 30px;">
                            <a href="' . (isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : '') . '/?page=order-track&term=' . $order->getMa_don_hang() . '" class="button-link" style="color: #ffffff;">Theo dõi đơn hàng</a>
                        </div>
                    </div>
                    <div class="footer">
                        Đây là email tự động từ hệ thống Bảo Đạt Sport. Quý khách vui lòng không phản hồi trực tiếp vào email này.<br>
                        Mọi thắc mắc xin liên hệ hotline hoặc gửi mail hỗ trợ khách hàng.<br><br>
                        &copy; ' . date("Y") . ' Bảo Đạt Sport. All rights reserved.
                    </div>
                </div>
            </body>
            </html>';

            $mail->send();
            return true;
        } catch (Exception $e) {
            file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] SMTP Invoice Error: " . $mail->ErrorInfo . "\r\n", FILE_APPEND);
            return false;
        }
    }
}
