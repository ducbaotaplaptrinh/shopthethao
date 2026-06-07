<?php
require BASE_PATH . "/app/controllers/sanphamcontroller.php";
return [
	'login' => [
		'title' => 'Đăng nhập',
		'view' => 'auth/login.php',

	],
	'register' => [
		'title' => 'Đăng ký',
		'view' => 'auth/register.php',

	],
	'home' => [
		'title' => 'Trang chủ',
		'view' => 'home/index.php',
		'controller' => \app\controllers\HomeController::class,
		'action' => 'index',
	],
	'product-index' => [
		'title' => 'Danh sách sản phẩm',
		'view' => 'product/index.php',
		'controller' => \app\controllers\SanPhamController::class,
		'action' => 'index',
		'pageStyles' => ['assets/css/category.css']
	],
	'product-detail' => [
		'title' => 'Chi tiết sản phẩm',
		'view' => 'product/product-detail.php',
		'controller' => \app\controllers\SanPhamController::class,
		'action' => 'chitiet'
	],
	'404' => [
		'title' => 'Không tìm thấy trang',
		'view' => 'errors/404.php',
	],
];
