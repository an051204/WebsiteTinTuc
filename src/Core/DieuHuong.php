<?php
namespace App\Core;

class DieuHuong
{
    protected array $routes = [];
    protected array $params = [];

    public function get(string $uri, array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $uri = preg_replace('#^/WebsiteTinTuc/public#', '', $uri);
        $uri = preg_replace('#^/WebsiteTinTuc#', '', $uri);
        
        if (empty($uri) || $uri === '') {
            $uri = '/';
        }
        
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }

        if ($uri !== '/' && substr($uri, -1) !== '/' && strpos($uri, '.') === false) {
            $uri .= '/';
        }

        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            $this->executeAction($action);
            return;
        }

        foreach ($this->routes[$method] ?? [] as $pattern => $action) {
            if (strpos($pattern, '{') !== false) {
                $regex = preg_replace('#\{([^}]+)\}#', '(?P<$1>[^/]+)', $pattern);
                $regex = '#^' . $regex . '/?$#';
                
                if (preg_match($regex, $uri, $matches)) {
                    foreach ($matches as $key => $value) {
                        if (!is_int($key)) {
                            $this->params[$key] = $value;
                        }
                    }
                    $this->executeAction($action);
                    return;
                }
            }
        }

        echo "<h1>Lỗi 404 - Không tìm thấy trang</h1>";
        echo "<p>URI tìm kiếm: " . htmlspecialchars($uri) . "</p>";
        echo "<p>Method: " . htmlspecialchars($method) . "</p>";
    }

    protected function executeAction(array $action): void
    {
        $controllerClass = $action[0];
        $methodName = $action[1];

        \App\Core\Controller::setRouteParams($this->params);

        $controller = new $controllerClass();
        $controller->$methodName();
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function route(): void
    {
        $this->resolve();
    }
}