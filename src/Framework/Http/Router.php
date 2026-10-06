<?php

declare(strict_types=1);

namespace App\Framework\Http;

use App\Framework\Exceptions\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

final class Router implements RequestHandlerInterface
{
    private array $routes = [];

    public function get(string $pattern, RequestHandlerInterface|callable $handler): void
    {
        $this->routes[] = ['GET', $pattern, $handler];
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->routes as [$method, $pattern, $handler]) {
            $regex = preg_replace('#\{([^}]+)\}#', '(?P<$1>[^/]+)', $pattern);
            $path = $request->getUri()->getPath();
            if ($method !== $request->getMethod() || !preg_match('#^' . $regex . '$#', $path, $matches)) {
                continue;
            }
            foreach (array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY) as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }
            $response = $handler instanceof RequestHandlerInterface
                ? $handler->handle($request)
                : $handler($request);
            if (!$response instanceof ResponseInterface) {
                throw new RuntimeException('Route handler must return a PSR-7 response');
            }
            return $response;
        }
        throw new NotFoundException('Route not found');
    }
}
