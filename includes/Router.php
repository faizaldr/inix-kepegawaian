<?php

class Router {
    private array $routes = [
        'GET'  => [],
        'POST' => []
    ];

    public function get(string $uri, $handler): void {
        $this->routes['GET'][$this->normalize($uri)] = $handler;
    }

    public function post(string $uri, $handler): void {
        $this->routes['POST'][$this->normalize($uri)] = $handler;
    }

    private function normalize(string $uri): string {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $trimmed = rtrim($path, '/');
        return $trimmed === '' ? '/' : $trimmed;
    }

    public function dispatch(string $requestUri, string $requestMethod): void {
        $method = strtoupper($requestMethod);
        $path = $this->normalize($requestUri);

        if (!isset($this->routes[$method])) {
            $this->render404("Metode HTTP {$method} tidak didukung.");
            return;
        }

        if (array_key_exists($path, $this->routes[$method])) {
            $handler = $this->routes[$method][$path];
            $this->executeHandler($handler);
            return;
        }

        $this->render404("Halaman '{$path}' tidak ditemukan.");
    }

    private function executeHandler($handler): void {
        if (is_callable($handler)) {
            call_user_func($handler);
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            if (class_exists($class)) {
                $instance = new $class();
                if (method_exists($instance, $method)) {
                    $instance->$method();
                    return;
                }
            }
        }

        http_response_code(500);
        die("Handler error: Target routing tidak dapat dieksekusi.");
    }

    private function render404(string $message): void {
        http_response_code(404);
        $pageTitle = '404 - Halaman Tidak Ditemukan | PT INIX Eduparx';
        require_once __DIR__ . '/../app/Views/layouts/header.php';
        echo '<div class="eduparx-container text-center py-5">';
        echo '<h1 class="display-4 text-danger font-weight-bold">404</h1>';
        echo '<p class="lead text-muted">' . htmlspecialchars($message) . '</p>';
        echo '<a href="/dashboard" class="btn btn-primary mt-3">Kembali ke Dashboard</a>';
        echo '</div>';
        require_once __DIR__ . '/../app/Views/layouts/footer.php';
        exit;
    }
}
