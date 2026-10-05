<?php

declare(strict_types=1);

namespace App\Providers;

use App\DI\Container;

interface ServiceProvider
{
    public function register(Container $container): void;

    public function boot(Container $container): void;
}
