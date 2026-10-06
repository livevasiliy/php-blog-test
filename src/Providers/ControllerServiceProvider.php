<?php

declare(strict_types=1);

namespace App\Providers;

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Framework\DI\Container;
use App\Framework\Providers\ServiceProvider;
use App\Requests\CategoryIndexRequest;

final class ControllerServiceProvider implements ServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(CategoryIndexRequest::class, static fn (): CategoryIndexRequest => new CategoryIndexRequest());

        foreach ([HomeController::class, CategoryController::class, ArticleController::class] as $controller) {
            $container->singleton($controller, static fn (Container $c) => $c->autowire($controller));
        }
    }

    public function boot(Container $container): void
    {
    }
}
