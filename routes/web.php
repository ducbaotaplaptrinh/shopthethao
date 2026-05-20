<?php

return [
	'home' => [
		'title' => 'Trang chủ',
		'view' => 'home/index.php',
		'data' => [
			'featuredProducts' => [
				['name' => 'Vợt cầu lông Yonex Astrox', 'price' => '2.450.000đ', 'tag' => 'Bán chạy'],
				['name' => 'Giày cầu lông Kason', 'price' => '1.390.000đ', 'tag' => 'Mới'],
				['name' => 'Áo thi đấu thể thao', 'price' => '320.000đ', 'tag' => 'Hot'],
			],
		],
	],
	'product' => [
		'title' => 'Sản phẩm',
		'view' => 'product/index.php',
	],
	'404' => [
		'title' => 'Không tìm thấy trang',
		'view' => 'errors/404.php',
	],
];
