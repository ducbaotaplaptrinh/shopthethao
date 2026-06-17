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
}
