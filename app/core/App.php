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
		$title = $route['title']  ?? 'Bảo Đạt Sport';

		if (isset($route['controller']) && isset($route['action'])) {
			$controllerClass = $route['controller'];
			$action = $route['action'];
			if (class_exists($controllerClass)) {
				$controllerInstance = new \app\controllers\SanPhamController();

				if (method_exists($controllerInstance, $action)) {
					$controllerData = $controllerInstance->$action();
					if (is_array($controllerData)) {
						// ket hop 2 mang lai ghi de vao cac tuoc tinh trong $route
						$data = array_merge($route, $controllerData);
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
// ham de debug
function dd($data)
{

	echo "<html style='background: #1d1f21;'><head><title>Debug Data</title></head><body>";
	echo "<pre style='background: #282a2e; color: #70c0b1; padding: 20px; border-radius: 8px; font-size: 15px; font-family: monospace; border: 1px solid #373b41; margin: 20px; overflow: auto;'>";
	var_dump($data);
	echo "</pre></body></html>";
	die();
}
