<?php

declare(strict_types=1);

namespace App\Providers;

use App\Framework\DI\Container;
use App\Framework\Providers\ServiceProvider;
use App\Repository\ArticleRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\PostgresArticleRepository;
use App\Repository\PostgresCategoryRepository;
use PDO;

final class RepositoryServiceProvider implements ServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(PostgresCategoryRepository::class, static fn (Container $c): PostgresCategoryRepository => new PostgresCategoryRepository($c->get(PDO::class)));
        $container->singleton(PostgresArticleRepository::class, static fn (Container $c): PostgresArticleRepository => new PostgresArticleRepository($c->get(PDO::class)));
        $container->singleton(CategoryRepositoryInterface::class, static fn (Container $c): CategoryRepositoryInterface => $c->get(PostgresCategoryRepository::class));
        $container->singleton(ArticleRepositoryInterface::class, static fn (Container $c): ArticleRepositoryInterface => $c->get(PostgresArticleRepository::class));
    }

    public function boot(Container $container): void
    {
    }
}
