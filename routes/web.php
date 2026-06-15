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
	'404' => [
		'title' => 'Không tìm thấy trang',
		'view' => 'errors/404.php',
	],
	'admin-dashboard' => [
		'title' => 'Dashboard | Quản trị',
		'view' => 'admin/dashboard.php',
		'controller' => \app\controllers\admin\AdminDashboardController::class,
		'action' => 'index',
		'pageStyles' => ['assets/css/admin.css']
	],
	'admin-products' => [
		'title' => 'Quản lý Sản phẩm | Quản trị',
		'view' => 'admin/product/index.php',
		'controller' => \app\controllers\admin\AdminProductController::class,
		'action' => 'index'
	],
	'admin-product-create' => [
		'title' => 'Thêm Sản phẩm | Quản trị',
		'view' => 'admin/product/form.php',
		'controller' => \app\controllers\admin\AdminProductController::class,
		'action' => 'create'
	],
	'admin-product-store' => [
		'title' => 'Lưu Sản phẩm | Quản trị',
		'view' => 'admin/product/index.php',
		'controller' => \app\controllers\admin\AdminProductController::class,
		'action' => 'store'
	],
	'admin-categories' => [
		'title' => 'Danh mục | Quản trị',
		'view' => 'admin/category/index.php',
		'controller' => \app\controllers\admin\AdminCategoryBrandController::class,
		'action' => 'categories'
	],
	'admin-category-store' => [
		'title' => 'Lưu Danh mục',
		'view' => 'admin/category/index.php',
		'controller' => \app\controllers\admin\AdminCategoryBrandController::class,
		'action' => 'storeCategory'
	],
	'admin-brands' => [
		'title' => 'Thương hiệu | Quản trị',
		'view' => 'admin/brand/index.php',
		'controller' => \app\controllers\admin\AdminCategoryBrandController::class,
		'action' => 'brands'
	],
	'admin-brand-store' => [
		'title' => 'Lưu Thương hiệu',
		'view' => 'admin/brand/index.php',
		'controller' => \app\controllers\admin\AdminCategoryBrandController::class,
		'action' => 'storeBrand'
	],
	'admin-attributes' => [
		'title' => 'Thuộc tính | Quản trị',
		'view' => 'admin/attribute/index.php',
		'controller' => \app\controllers\admin\AdminAttributeController::class,
		'action' => 'index'
	],
	'admin-attribute-store-group' => [
		'title' => 'Lưu Nhóm Thuộc tính',
		'view' => 'admin/attribute/index.php',
		'controller' => \app\controllers\admin\AdminAttributeController::class,
		'action' => 'storeGroup'
	],
	'admin-attribute-store-value' => [
		'title' => 'Lưu Giá trị Thuộc tính',
		'view' => 'admin/attribute/index.php',
		'controller' => \app\controllers\admin\AdminAttributeController::class,
		'action' => 'storeValue'
	],
	'admin-attribute-toggle' => [
		'title' => 'Đổi trạng thái biến thể',
		'view' => 'errors/404.php', // API endpoint
		'controller' => \app\controllers\admin\AdminAttributeController::class,
		'action' => 'toggleVariant'
	],
	'admin-orders' => [
		'title' => 'Quản lý Đơn hàng | Quản trị',
		'view' => 'admin/order/index.php',
		'controller' => \app\controllers\admin\AdminOrderController::class,
		'action' => 'index'
	],
	'admin-order-detail' => [
		'title' => 'Chi tiết Đơn hàng | Quản trị',
		'view' => 'admin/order/detail.php',
		'controller' => \app\controllers\admin\AdminOrderController::class,
		'action' => 'detail'
	],
	'admin-order-update-status' => [
		'title' => 'Cập nhật trạng thái',
		'view' => 'admin/order/detail.php',
		'controller' => \app\controllers\admin\AdminOrderController::class,
		'action' => 'updateStatus'
	],
	'admin-customers' => [
		'title' => 'Quản lý Khách hàng | Quản trị',
		'view' => 'admin/customer/index.php',
		'controller' => \app\controllers\admin\AdminCustomerController::class,
		'action' => 'index'
	],
	'admin-customer-toggle' => [
		'title' => 'Đổi trạng thái tài khoản',
		'view' => 'admin/customer/index.php',
		'controller' => \app\controllers\admin\AdminCustomerController::class,
		'action' => 'toggleStatus'
	],
	'contact' => [
		'title' => 'Liên hệ',
		'view' => 'contact/contact.php',
		'pageStyles' => ['assets/css/contact.css']
	],
];
