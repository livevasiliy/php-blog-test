<?php

declare(strict_types=1);

namespace App\Framework\Providers;

use App\Framework\DI\Container;

interface ServiceProvider
{
    public function register(Container $container): void;

    public function boot(Container $container): void;
}
