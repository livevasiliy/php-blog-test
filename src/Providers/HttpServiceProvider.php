<?php

declare(strict_types=1);

namespace App\Providers;

use App\Framework\DI\Container;
use App\Framework\Http\{ExceptionResponseHandler, HtmlResponseFactory, ResponseEmitter, ResponseFactory, ServerRequestFactory, StreamFactory};
use App\Framework\Http\Middleware\ErrorMiddleware;
use App\Framework\Providers\ServiceProvider;
use App\Framework\View\ViewResponseFactory;

final class HttpServiceProvider implements ServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(ResponseFactory::class, static fn (): ResponseFactory => new ResponseFactory());
        $container->singleton(StreamFactory::class, static fn (): StreamFactory => new StreamFactory());
        $container->singleton(HtmlResponseFactory::class, static fn (Container $c): HtmlResponseFactory => new HtmlResponseFactory($c->get(ResponseFactory::class), $c->get(StreamFactory::class)));
        $container->singleton(ExceptionResponseHandler::class, static fn (Container $c): ExceptionResponseHandler => new ExceptionResponseHandler($c->get(ViewResponseFactory::class)));
        $container->singleton(ServerRequestFactory::class, static fn (): ServerRequestFactory => new ServerRequestFactory());
        $container->singleton(ResponseEmitter::class, static fn (): ResponseEmitter => new ResponseEmitter());
        $container->singleton(ErrorMiddleware::class, static fn (Container $c): ErrorMiddleware => new ErrorMiddleware($c->get(ExceptionResponseHandler::class)));
    }

    public function boot(Container $container): void
    {
    }
}
