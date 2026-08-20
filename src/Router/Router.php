<?php

declare(strict_types=1);

namespace App\Router;

class Router
{
    private array $routes = [];

    public function get(
        string $path,
        callable $handler
    ): void {
        $this->add('GET', $path, $handler);
    }

    public function post(
        string $path,
        callable $handler
    ): void {
        $this->add('POST', $path, $handler);
    }

    public function put(
        string $path,
        callable $handler
    ): void {
        $this->add('PUT', $path, $handler);
    }

    public function delete(
        string $path,
        callable $handler
    ): void {
        $this->add('DELETE', $path, $handler);
    }

    private function add(
        string $method,
        string $path,
        callable $handler
    ): void {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = parse_url(
            $_SERVER['REQUEST_URI'],
            PHP_URL_PATH
        );

        foreach ($this->routes[$method] ?? [] as $path => $handler) {
            $pattern = preg_replace(
                '#\{[^/]+\}#',
                '([^/]+)',
                $path
            );

            if ($pattern === null) {
                continue;
            }

            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $handler(...$matches);

                return;
            }
        }

        http_response_code(404);

        header('Content-Type: application/json');

        echo json_encode([
            'erro' => 'Rota não encontrada'
        ]);
    }
}