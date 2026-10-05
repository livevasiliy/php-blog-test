<?php

declare(strict_types=1);

namespace App\Http;

use RuntimeException;

final class Router
{
    private array $routes = [];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->routes[] = ['GET', $pattern, $handler];
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as [$method, $pattern, $handler]) {
            $regex = preg_replace('#\{([^}]+)\}#', '(?P<$1>[^/]+)', $pattern);
            if ($method !== $request->method || !preg_match('#^' . $regex . '$#', $request->path, $matches)) {
                continue;
            }
            $parameters = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return $handler(...array_values($parameters));
        }
        throw new RuntimeException('Route not found', 404);
    }
}
