<?php

declare(strict_types=1);

if (!defined('BASE_PATH')) {
	define('BASE_PATH', dirname(__DIR__, 2));
}

class App
{
	public function run(): void
	{
		$routes = require BASE_PATH . '/routes/web.php';
		$page = $_GET['page'] ?? 'home';
		$route = $routes[$page] ?? $routes['404'];

		$view = BASE_PATH . '/app/views/' . $route['view'];
		$data = $route['data'] ?? [];
		$title = $route['title'] ?? 'Shop Thể Thao';

		$this->render($view, $data, $title);
	}

	private function render(string $view, array $data = [], string $title = 'Shop Thể Thao'): void
	{
		extract($data, EXTR_SKIP);

		ob_start();
		require $view;
		$content = ob_get_clean();

		require BASE_PATH . '/app/views/layouts/main.php';
	}
}
