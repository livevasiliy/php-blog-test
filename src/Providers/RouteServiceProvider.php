<?php

declare(strict_types=1);

namespace App\Providers;

use App\Framework\DI\Container;
use App\Framework\Http\Router;
use RuntimeException;

final class RouteServiceProvider implements ServiceProvider
{
    public function __construct(private readonly string $rootDir)
    {
    }

    public function register(Container $container): void
    {
        $container->singleton(Router::class, static fn (): Router => new Router());
    }

    public function boot(Container $container): void
    {
        $routes = require $this->rootDir . '/routes/web.php';
        if (!is_callable($routes)) {
            throw new RuntimeException('Route file must return a callable');
        }

        $routes($container->get(Router::class), $container);
    }
}
