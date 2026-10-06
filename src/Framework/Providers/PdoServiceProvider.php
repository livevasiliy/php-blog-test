<?php

declare(strict_types=1);

namespace App\Framework\Providers;

use App\Framework\Database\PdoFactory;
use App\Framework\DI\Container;
use PDO;

final class PdoServiceProvider implements ServiceProvider
{
    public function __construct(private readonly string $rootDir)
    {
    }

    public function register(Container $container): void
    {
        $database = require $this->rootDir . '/config/database.php';

        $container->singleton(PdoFactory::class, static fn (): PdoFactory => new PdoFactory());
        $container->singleton(PDO::class, static fn (Container $c): PDO => $c->get(PdoFactory::class)->create($database));
    }

    public function boot(Container $container): void
    {
    }
}
