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
		$title = $route['title'] ?? 'Bảo Đạt Sport';
		if (isset($route['controller']) && isset($route['action'])) {
			$controllerClass = $route['controller'];
			$action = $route['action'];
			if (class_exists($controllerClass)) {
				$controllerInstance = new $controllerClass();
				if (method_exists($controllerInstance, $action)) {
					$controllerData = $controllerInstance->$action();
					if (is_array($controllerData)) {
						// ket hop 2 mang lai
						$data = array_merge($data, $controllerData);
					}
				}
			}
		}
		$this->render($view, $data, $title);
	}

	private function render(string $view, array $data = [], string $title = 'Bảo Đạt Sport'): void
	{
		extract($data, EXTR_SKIP);
		ob_start();
		if ($view) {
			require $view;
		} else {
			require BASE_PATH . '/app/views/errors/404.php';
		}
		$content = ob_get_clean();

		require BASE_PATH . '/app/views/layouts/main.php';
	}
}
