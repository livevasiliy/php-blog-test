<?php

declare(strict_types=1);

namespace App;

use App\DI\Container;
use App\Http\{ResponseEmitter, Router, ServerRequestFactory};
use App\Http\Middleware\{ErrorMiddleware, MiddlewareStack};
use App\Providers\{ApplicationServiceProvider, RouteServiceProvider};
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Kernel
{
    private Container $container;
    private RequestHandlerInterface $handler;

    public function __construct(private readonly string $rootDir)
    {
        $this->container = new Container();
        $this->registerProviders();
        $router = $this->container->get(Router::class);
        $this->handler = new MiddlewareStack([$this->container->get(ErrorMiddleware::class)], $router);
    }

    public function handle(?ServerRequestInterface $request = null): void
    {
        $request ??= $this->container->get(ServerRequestFactory::class)->fromGlobals();
        $response = $this->handler->handle($request);
        $this->container->get(ResponseEmitter::class)->emit($response);
    }

    private function registerProviders(): void
    {
        $providers = [
            new ApplicationServiceProvider($this->rootDir),
            new RouteServiceProvider($this->rootDir),
        ];

        foreach ($providers as $provider) {
            $provider->register($this->container);
        }

        foreach ($providers as $provider) {
            $provider->boot($this->container);
        }
    }
}
