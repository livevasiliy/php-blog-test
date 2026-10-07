<?php

declare(strict_types=1);

namespace App\Framework\Providers;

use App\Framework\DI\Container;
use App\Framework\Http\HtmlResponseFactory;
use App\Framework\View\AssetManager;
use App\Framework\View\SmartyView;
use App\Framework\View\ViewResponseFactory;
use Smarty;

final class ViewServiceProvider implements ServiceProvider
{
    public function __construct(private readonly string $rootDir)
    {
    }

    public function register(Container $container): void
    {
        $rootDir = $this->rootDir;
        $templateDir = $rootDir . '/templates';
        $compileDir = $rootDir . '/storage/compile';
        $cacheDir = $rootDir . '/storage/cache';

        foreach ([$compileDir, $cacheDir] as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }
        }

        $container->singleton(AssetManager::class, static fn (): AssetManager => new AssetManager($rootDir . '/public/build'));
        $container->singleton(Smarty::class, static function () use ($templateDir, $compileDir, $cacheDir): Smarty {
            $smarty = new Smarty();
            $smarty->setTemplateDir($templateDir);
            $smarty->setCompileDir($compileDir);
            $smarty->setCacheDir($cacheDir);

            return $smarty;
        });
        $container->singleton(SmartyView::class, static fn (Container $c): SmartyView => new SmartyView($c->get(Smarty::class), $c->get(AssetManager::class)));
        $container->singleton(ViewResponseFactory::class, static fn (Container $c): ViewResponseFactory => new ViewResponseFactory($c->get(SmartyView::class), $c->get(HtmlResponseFactory::class)));
    }

    public function boot(Container $container): void
    {
    }
}
