<?php

function formatVND($price)
{
    return number_format($price, 0, ',', '.') . ' ₫';
}

function getProductImage($imagePath)
{
    $placeholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'><rect width='100%' height='100%' fill='%23f3f4f6'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='14' fill='%239ca3af'>Không có ảnh</text></svg>";

    if (empty($imagePath)) {
        return $placeholder;
    }

    $cleanPath = trim($imagePath); //xóa khoảng trắng
    //strpos kiểm trả chuỗi có  bắt đầu bằng assets không 
    if (strpos($cleanPath, 'assets/') !== 0) {
        $cleanPath = 'assets/images/' . $cleanPath;
    }

    if (file_exists(BASE_PATH . '/public/' . $cleanPath)) {
        return $cleanPath;
    }

    return $placeholder;
}

function getPaginationUrl($pageNum)
{
    $params = $_GET;
    $params['p'] = $pageNum;
    return '?' . http_build_query($params);
}
