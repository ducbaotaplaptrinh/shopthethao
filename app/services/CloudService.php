<?php
namespace app\services;

class CloudService
{

    // Đã điền sẵn Cloud Name của bạn
    private static $cloudName = 'deykui3fy';

    // Nếu bạn đặt tên khác ở bước 4, hãy sửa lại chữ baodat_upload cho khớp nhé
    private static $uploadPreset = 'baodat_upload';

    /**
     * Hàm upload ảnh lên Cloudinary
     * @param array $file Thường là $_FILES['anh_dai_dien']
     * @return string|false Trả về URL của ảnh (https) nếu thành công, false nếu lỗi
     */
    public static function uploadImage($file)
    {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return false;
        }

        $tmpFilePath = $file['tmp_name'];
        $apiUrl = "https://api.cloudinary.com/v1_1/" . self::$cloudName . "/image/upload";

        $ch = curl_init();

        $postFields = [
            'file' => new \CURLFile($tmpFilePath, $file['type'], $file['name']),
            'upload_preset' => self::$uploadPreset
        ];

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $responseData = json_decode($response, true);
            return $responseData['secure_url'];
        }

        error_log("Lỗi upload Cloudinary: " . $response);
        return false;
    }
}