<!-- kiểm tra tiêu đề đã được đặt chưa, nếu chưa thì đặt mặc định -->
<?php
if (!isset($title)) {
    $title = 'Bảo Đạt Sport';
}

?>

<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="assets/css/reset.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" />
    <!-- Icon -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <!-- Hiệu ứng Swiper -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
    <!-- <link rel="stylesheet" href="assets/css/reset.css"> -->
    <link rel="stylesheet" href="assets/css/global.css">
</head>

<body>
    <?php
    require __DIR__ . "/../components/navbar.php";
