<?php

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

	],
	'product-index' => [
		'title' => 'sản phẩm',
		'view' => 'product/index.php',
		'controller' => \App\controllers\SanPhamController::class,
		'action' => 'index',
	],
	'product-detail' => [
		'title' => 'Chi tiết Sản phẩm',
		'view' => 'product/product-detail.php',
		'controller' => \App\controllers\SanPhamController::class,
		'action' => 'chitietsanpham'
	],
	'404' => [
		'title' => 'Không tìm thấy trang',
		'view' => 'errors/404.php',
	],
];
