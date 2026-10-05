<?php

declare(strict_types=1);

namespace App\Providers;

use App\Framework\DI\Container;
use App\Framework\Providers\ServiceProvider;
use App\Service\BlogService;
use App\Repository\{ArticleRepositoryInterface, CategoryRepositoryInterface};

final class BlogServiceProvider implements ServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(BlogService::class, static fn (Container $c): BlogService => new BlogService($c->get(CategoryRepositoryInterface::class), $c->get(ArticleRepositoryInterface::class)));
    }

    public function boot(Container $container): void
    {
    }
}
