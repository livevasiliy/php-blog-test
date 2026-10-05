<?php

declare(strict_types=1);

namespace App;

use App\Controller\{ArticleController, CategoryController, HomeController};
use App\DI\Container;
use App\Http\{Request, Response, Router};
use App\Requests\CategoryIndexRequest;
use App\Repository\{ArticleRepositoryInterface, CategoryRepositoryInterface, PostgresArticleRepository, PostgresCategoryRepository};
use App\Service\BlogService;
use App\View\{AssetManager, PhpView};
use PDO;
use RuntimeException;

final class Kernel
{
    private Container $container;
    private Router $router;

    public function __construct(private readonly string $rootDir)
    {
        $this->container = $this->buildContainer();
        $this->router = $this->buildRouter();
    }

    public function handle(?Request $request = null): void
    {
        try {
            ($this->router->dispatch($request ?? Request::fromGlobals()))->send();
        } catch (RuntimeException $exception) {
            $status = $exception->getCode() === 404 ? 404 : 500;
            (new Response($status === 404 ? '<h1>404 Not Found</h1>' : '<h1>500 Internal Server Error</h1>', $status))->send();
        }
    }

    private function buildContainer(): Container
    {
        $container = new Container();
        $database = require $this->rootDir . '/config/database.php';
        $container->singleton(Request::class, fn (): Request => Request::fromGlobals());
        $container->singleton(CategoryIndexRequest::class, fn (Container $c): CategoryIndexRequest => new CategoryIndexRequest($c->get(Request::class)));
        $container->singleton(PDO::class, fn (): PDO => $this->createConnection($database));
        $container->singleton(AssetManager::class, fn (): AssetManager => new AssetManager($this->rootDir . '/public/build'));
        $container->singleton(PhpView::class, fn (Container $c): PhpView => new PhpView($this->rootDir . '/templates', $c->get(AssetManager::class)));
        $container->singleton(PostgresCategoryRepository::class, fn (Container $c): PostgresCategoryRepository => new PostgresCategoryRepository($c->get(PDO::class)));
        $container->singleton(PostgresArticleRepository::class, fn (Container $c): PostgresArticleRepository => new PostgresArticleRepository($c->get(PDO::class)));
        $container->singleton(CategoryRepositoryInterface::class, fn (Container $c): CategoryRepositoryInterface => $c->get(PostgresCategoryRepository::class));
        $container->singleton(ArticleRepositoryInterface::class, fn (Container $c): ArticleRepositoryInterface => $c->get(PostgresArticleRepository::class));
        $container->singleton(BlogService::class, fn (Container $c): BlogService => new BlogService($c->get(CategoryRepositoryInterface::class), $c->get(ArticleRepositoryInterface::class)));
        foreach ([HomeController::class, CategoryController::class, ArticleController::class] as $controller) {
            $container->singleton($controller, fn (Container $c) => $c->autowire($controller));
        }
        return $container;
    }

    private function buildRouter(): Router
    {
        $router = new Router();
        $router->get('/', [$this->container->get(HomeController::class), 'index']);
        $router->get('/category/{slug}', [$this->container->get(CategoryController::class), 'show']);
        $router->get('/article/{slug}', [$this->container->get(ArticleController::class), 'show']);
        return $router;
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
