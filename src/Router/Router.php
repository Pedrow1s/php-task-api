<?php

declare(strict_types=1);

namespace App\Router;

class Router
{
    public function get(string $path, callable $handler): void
    {
        if ( $_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === $path) {
            $handler();
        }
    }
}