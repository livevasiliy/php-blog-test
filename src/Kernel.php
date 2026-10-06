<?php

declare(strict_types=1);

namespace App;

use App\Framework\DI\Container;
use App\Framework\Http\ResponseEmitter;
use App\Framework\Http\Router;
use App\Framework\Http\ServerRequestFactory;
use App\Framework\Http\Middleware\ErrorMiddleware;
use App\Framework\Http\Middleware\MiddlewareStack;
use App\Providers\BlogServiceProvider;
use App\Providers\ControllerServiceProvider;
use App\Providers\HttpServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\ViewServiceProvider;
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
            new HttpServiceProvider(),
            new ViewServiceProvider($this->rootDir),
            new RepositoryServiceProvider($this->rootDir),
            new BlogServiceProvider(),
            new ControllerServiceProvider(),
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
