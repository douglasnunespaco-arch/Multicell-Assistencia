<?php
namespace App\Core;

/**
 * Router — front controller simples com suporte a parâmetros {slug}.
 *
 * Uso (em app/routes.php):
 *   $router->get('/', 'Public\\HomeController@index');
 *   $router->post('/reservar', 'Public\\ReservationController@store');
 *   $router->get('/produtos/{slug}', 'Public\\ProductsController@show');
 */
final class Router
{
    /** @var array<string, array<int, array{pattern:string, handler:string|callable, params:array}>> */
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    public function get(string $path, $handler): void    { $this->add('GET', $path, $handler); }
    public function post(string $path, $handler): void   { $this->add('POST', $path, $handler); }

    private function add(string $method, string $path, $handler): void
    {
        $params = [];
        $pattern = preg_replace_callback('#\{([a-zA-Z_]+)\}#', function ($m) use (&$params) {
            $params[] = $m[1];
            return '([^/]+)';
        }, rtrim($path, '/') ?: '/');
        $pattern = '#^' . $pattern . '$#';
        $this->routes[$method][] = compact('pattern', 'handler', 'params');
    }

    public function dispatch(string $url): void
    {
        $url    = '/' . trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        if ($url === '') $url = '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $url, $matches)) {
                array_shift($matches);
                $values = array_combine($route['params'], $matches) ?: [];
                $this->invoke($route['handler'], $values);
                return;
            }
        }
        $this->notFound();
    }

    private function invoke($handler, array $params): void
    {
        if (is_callable($handler)) {
            echo $handler($params);
            return;
        }
        if (is_string($handler) && str_contains($handler, '@')) {
            [$class, $action] = explode('@', $handler, 2);
            $fqcn = 'App\\Controllers\\' . str_replace('/', '\\', $class);
            if (!class_exists($fqcn)) {
                $this->notFound();
                return;
            }
            $instance = new $fqcn();
            if (!method_exists($instance, $action)) {
                $this->notFound();
                return;
            }
            echo $instance->$action($params);
            return;
        }
        $this->notFound();
    }

    private function notFound(): void
    {
        http_response_code(404);
        $view = APP_DIR . '/Views/public/404.php';
        if (is_file($view)) {
            require $view;
        } else {
            echo '<h1>404 — Página não encontrada</h1>';
        }
    }
}
