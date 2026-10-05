<?php

declare(strict_types=1);

namespace App\Providers;

use App\Framework\DI\Container;
use App\Framework\Providers\ServiceProvider;
use App\Repository\{ArticleRepositoryInterface, CategoryRepositoryInterface, PostgresArticleRepository, PostgresCategoryRepository};
use PDO;

final class RepositoryServiceProvider implements ServiceProvider
{
    public function __construct(private readonly string $rootDir)
    {
    }

    public function register(Container $container): void
    {
        $database = require $this->rootDir . '/config/database.php';

        $container->singleton(PDO::class, fn (): PDO => $this->createConnection($database));
        $container->singleton(PostgresCategoryRepository::class, static fn (Container $c): PostgresCategoryRepository => new PostgresCategoryRepository($c->get(PDO::class)));
        $container->singleton(PostgresArticleRepository::class, static fn (Container $c): PostgresArticleRepository => new PostgresArticleRepository($c->get(PDO::class)));
        $container->singleton(CategoryRepositoryInterface::class, static fn (Container $c): CategoryRepositoryInterface => $c->get(PostgresCategoryRepository::class));
        $container->singleton(ArticleRepositoryInterface::class, static fn (Container $c): ArticleRepositoryInterface => $c->get(PostgresArticleRepository::class));
    }

    public function boot(Container $container): void
    {
    }

    private function createConnection(array $config): PDO
    {
        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $config['host'], $config['port'], $config['database']);

        return new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
