<?php

namespace App;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $routes = $this->routes[$method] ?? [];

        if ($method === 'POST' && $this->requiresCsrf($path) && !verify_csrf()) {
            http_response_code(419);
            flash('error', 'Phiên không hợp lệ. Vui lòng thử lại.');
            redirect($this->csrfFallbackPath($path));
        }

        if (isset($routes[$path])) {
            $routes[$path]();
            return;
        }

        foreach ($routes as $pattern => $handler) {
            $regex = '#^' . preg_replace('#\{([a-zA-Z_]+)\}#', '([^/]+)', $pattern) . '$#';
            if (preg_match($regex, $path, $matches)) {
                array_shift($matches);
                $handler(...$matches);
                return;
            }
        }

        http_response_code(404);
        view('client.404');
    }

    private function requiresCsrf(string $path): bool
    {
        if (str_starts_with($path, '/api/')) {
            return in_array($path, [
                '/api/support/send',
                '/api/support/typing',
                '/api/shipper/location',
            ], true);
        }

        if ($path === '/payment/momo/callback' || $path === '/payment/vietqr/webhook') {
            return false;
        }

        return true;
    }

    private function csrfFallbackPath(string $path): string
    {
        if (str_starts_with($path, '/admin')) {
            return '/admin/login';
        }

        if (str_starts_with($path, '/warranty')) {
            return '/warranty';
        }

        return '/';
    }
}
