<?php

declare(strict_types=1);

namespace App\Providers;

use App\Framework\DI\Container;
use App\Framework\Http\HtmlResponseFactory;
use App\Framework\Providers\ServiceProvider;
use App\Framework\View\AssetManager;
use App\Framework\View\PhpView;
use App\Framework\View\ViewResponseFactory;

final class ViewServiceProvider implements ServiceProvider
{
    public function __construct(private readonly string $rootDir)
    {
    }

    public function register(Container $container): void
    {
        $rootDir = $this->rootDir;

        $container->singleton(AssetManager::class, static fn (): AssetManager => new AssetManager($rootDir . '/public/build'));
        $container->singleton(PhpView::class, static fn (Container $c): PhpView => new PhpView($rootDir . '/templates', $c->get(AssetManager::class)));
        $container->singleton(ViewResponseFactory::class, static fn (Container $c): ViewResponseFactory => new ViewResponseFactory($c->get(PhpView::class), $c->get(HtmlResponseFactory::class)));
    }

    public function boot(Container $container): void
    {
    }
}
