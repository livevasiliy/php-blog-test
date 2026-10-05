<?php

declare(strict_types=1);

namespace App\Providers;

use App\Controller\{ArticleController, CategoryController, HomeController};
use App\Framework\DI\Container;
use App\Framework\Http\{ExceptionResponseHandler, HtmlResponseFactory, ResponseEmitter, ResponseFactory, ServerRequestFactory, StreamFactory};
use App\Framework\Http\Middleware\ErrorMiddleware;
use App\Framework\Providers\ServiceProvider;
use App\Requests\CategoryIndexRequest;
use App\Repository\{ArticleRepositoryInterface, CategoryRepositoryInterface, PostgresArticleRepository, PostgresCategoryRepository};
use App\Service\BlogService;
use App\Framework\View\{AssetManager, PhpView, ViewResponseFactory};
use PDO;

final class ApplicationServiceProvider implements ServiceProvider
{
    public function __construct(private readonly string $rootDir)
    {
    }

    public function register(Container $container): void
    {
        $database = require $this->rootDir . '/config/database.php';
        $rootDir = $this->rootDir;

        $container->singleton(ResponseFactory::class, static fn (): ResponseFactory => new ResponseFactory());
        $container->singleton(StreamFactory::class, static fn (): StreamFactory => new StreamFactory());
        $container->singleton(HtmlResponseFactory::class, static fn (Container $c): HtmlResponseFactory => new HtmlResponseFactory($c->get(ResponseFactory::class), $c->get(StreamFactory::class)));
        $container->singleton(ExceptionResponseHandler::class, static fn (Container $c): ExceptionResponseHandler => new ExceptionResponseHandler($c->get(HtmlResponseFactory::class), $c->get(PhpView::class)));
        $container->singleton(ServerRequestFactory::class, static fn (): ServerRequestFactory => new ServerRequestFactory());
        $container->singleton(ResponseEmitter::class, static fn (): ResponseEmitter => new ResponseEmitter());
        $container->singleton(ErrorMiddleware::class, static fn (Container $c): ErrorMiddleware => new ErrorMiddleware($c->get(ExceptionResponseHandler::class)));
        $container->singleton(CategoryIndexRequest::class, static fn (): CategoryIndexRequest => new CategoryIndexRequest());
        $container->singleton(PDO::class, fn (): PDO => $this->createConnection($database));
        $container->singleton(AssetManager::class, static fn (): AssetManager => new AssetManager($rootDir . '/public/build'));
        $container->singleton(PhpView::class, static fn (Container $c): PhpView => new PhpView($rootDir . '/templates', $c->get(AssetManager::class)));
        $container->singleton(ViewResponseFactory::class, static fn (Container $c): ViewResponseFactory => new ViewResponseFactory($c->get(PhpView::class), $c->get(HtmlResponseFactory::class)));
        $container->singleton(PostgresCategoryRepository::class, static fn (Container $c): PostgresCategoryRepository => new PostgresCategoryRepository($c->get(PDO::class)));
        $container->singleton(PostgresArticleRepository::class, static fn (Container $c): PostgresArticleRepository => new PostgresArticleRepository($c->get(PDO::class)));
        $container->singleton(CategoryRepositoryInterface::class, static fn (Container $c): CategoryRepositoryInterface => $c->get(PostgresCategoryRepository::class));
        $container->singleton(ArticleRepositoryInterface::class, static fn (Container $c): ArticleRepositoryInterface => $c->get(PostgresArticleRepository::class));
        $container->singleton(BlogService::class, static fn (Container $c): BlogService => new BlogService($c->get(CategoryRepositoryInterface::class), $c->get(ArticleRepositoryInterface::class)));

        foreach ([HomeController::class, CategoryController::class, ArticleController::class] as $controller) {
            $container->singleton($controller, static fn (Container $c) => $c->autowire($controller));
        }
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
