<?php
require BASE_PATH . "/app/controllers/sanphamcontroller.php";
return [
	'login' => [
		'title' => 'Đăng nhập',
		'view' => 'auth/login.php',
		'controller' => \app\controllers\AuthController::class,
		'action' => 'login',
	],
	'register' => [
		'title' => 'Đăng ký',
		'view' => 'auth/register.php',
		'controller' => \app\controllers\AuthController::class,
		'action' => 'register',
	],
	'logout' => [
		'title' => 'Đăng xuất',
		'view' => 'auth/login.php',
		'controller' => \app\controllers\AuthController::class,
		'action' => 'logout',
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
		'pageStyles' => ['assets/css/product.css']
	],
	'flash-sale' => [
		'title' => 'Sản phẩm khuyến mãi | Bảo Đạt Sport',
		'view' => 'product/index.php',
		'controller' => \app\controllers\SanPhamController::class,
		'action' => 'flashSale',
		'pageStyles' => ['assets/css/product.css']
	],
	'product-detail' => [
		'title' => 'Chi tiết sản phẩm',
		'view' => 'product/product-detail.php',
		'controller' => \app\controllers\SanPhamController::class,
		'action' => 'chitiet',
		'pageStyles' => ['assets/css/product-detail.css']
	],
	'search-suggest' => [
		'title' => 'Gợi ý tìm kiếm',
		'view' => 'errors/404.php',
		'controller' => \app\controllers\SanPhamController::class,
		'action' => 'suggest',
	],

	'about' => [
		'title' => 'Giới thiệu',
		'view' => 'about/about.php',
	],
	'cart' => [
		'title' => 'Giỏ hàng | Bảo Đạt Sport',
		'view' => 'cart/index.php',
		'controller' => \app\controllers\CartController::class,
		'action' => 'index',
		'pageStyles' => ['assets/css/cart.css']
	],
	'cart-add' => [
		'title' => 'Thêm vào giỏ hàng',
		'view' => 'cart/index.php',
		'controller' => \app\controllers\CartController::class,
		'action' => 'add'
	],
	'cart-update' => [
		'title' => 'Cập nhật giỏ hàng',
		'view' => 'cart/index.php',
		'controller' => \app\controllers\CartController::class,
		'action' => 'update'
	],
	'cart-delete' => [
		'title' => 'Xoá giỏ hàng',
		'view' => 'cart/index.php',
		'controller' => \app\controllers\CartController::class,
		'action' => 'delete'
	],
	'checkout' => [
		'title' => 'Thanh toán đơn hàng | Bảo Đạt Sport',
		'view' => 'order/checkout.php',
		'controller' => \app\controllers\OrderController::class,
		'action' => 'checkout',
		'pageStyles' => ['assets/css/cart.css']
	],
	'order-place' => [
		'title' => 'Đặt hàng',
		'view' => 'order/checkout.php',
		'controller' => \app\controllers\OrderController::class,
		'action' => 'place'
	],
	'order-success' => [
		'title' => 'Đặt hàng thành công | Bảo Đạt Sport',
		'view' => 'order/success.php',
		'controller' => \app\controllers\OrderController::class,
		'action' => 'success',
		'pageStyles' => ['assets/css/cart.css']
	],
	'order-track' => [
		'title' => 'Tra cứu đơn hàng | Bảo Đạt Sport',
		'view' => 'order/track.php',
		'controller' => \app\controllers\OrderController::class,
		'action' => 'track',
		'pageStyles' => ['assets/css/cart.css']
	],
	'my-orders' => [
		'title' => 'Đơn hàng của tôi | Bảo Đạt Sport',
		'view' => 'order/my-orders.php',
		'controller' => \app\controllers\OrderController::class,
		'action' => 'myOrders',
		'pageStyles' => ['assets/css/my-orders.css']
	],
	'profile' => [
		'title' => 'Thông tin cá nhân | Bảo Đạt Sport',
		'view' => 'auth/profile.php',
		'controller' => \app\controllers\AuthController::class,
		'action' => 'profile',
		'pageStyles' => ['assets/css/profile.css']
	],
	'404' => [
		'title' => 'Không tìm thấy trang',
		'view' => 'errors/404.php',
	],
	'contact' => [
		'title' => 'Liên hệ',
		'view' => 'contact/contact.php',
		'pageStyles' => ['assets/css/contact.css']
	],
	'new' => [
		'title' => 'Tin tức thể thao',
		'view' => 'news/index.php',
		'controller' => \app\controllers\NewsController::class,
		'action' => 'index',
		'pageStyles' => ['assets/css/news.css']
	],
	'new-detail' => [
		'title' => 'Chi tiết tin tức',
		'view' => 'news/detail.php',
		'controller' => \app\controllers\NewsController::class,
		'action' => 'detail',
		'pageStyles' => ['assets/css/news.css']
	],
];
