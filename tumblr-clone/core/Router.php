<?php

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->routes = require __DIR__ . '/../config/routes.php';
    }

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        $route = str_replace($base, '', $uri);
        $route = '/' . trim($route, '/');

        // Extract route parameters (e.g., /profile/username)
        $params = [];
        foreach ($this->routes as $pattern => $handler) {
            $regex = preg_replace('/\{[a-z]+\}/', '([^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $route, $matches)) {
                array_shift($matches);
                $params = $matches;

                [$controllerName, $method] = $handler;
                $file = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';

                if (!file_exists($file)) {
                    $this->notFound();
                    return;
                }

                require_once $file;
                $controller = new $controllerName();
                call_user_func_array([$controller, $method], $params);
                return;
            }
        }

        $this->notFound();
    }

    private function notFound(): void
    {
        http_response_code(404);
        require __DIR__ . '/../app/Views/errors/404.php';
    }
}
