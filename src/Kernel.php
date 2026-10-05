<?php

declare(strict_types=1);

namespace App;

use App\Controller\{ArticleController, CategoryController, HomeController};
use App\Repository\{ArticleRepositoryInterface, CategoryRepositoryInterface, PostgresArticleRepository, PostgresCategoryRepository};
use App\Service\BlogService;
use App\View\SmartyView;
use Smarty;
use Doctrine\DBAL\{Connection, DriverManager};
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\{RequestContext, RouteCollection};
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Kernel
{
    private ContainerBuilder $container;

    public function __construct(private readonly string $rootDir)
    {
        $this->container = $this->buildContainer();
    }

    public function handle(?Request $request = null): void
    {
        $request ??= Request::createFromGlobals();
        $routes = require $this->rootDir . '/routes/web.php';
        $context = (new RequestContext())->fromRequest($request);

        try {
            $attributes = (new UrlMatcher($routes, $context))->match($request->getPathInfo());
            $controller = $this->container->get($attributes['_controller'][0]);
            $action = $attributes['_controller'][1];
            unset($attributes['_route'], $attributes['_controller']);
            $response = $controller->{$action}(...array_values($attributes));
        } catch (ResourceNotFoundException) {
            $response = new Response('<h1>404 Not Found</h1>', Response::HTTP_NOT_FOUND);
        }

        if (!$response instanceof Response) {
            throw new \RuntimeException('A controller must return a Symfony Response.');
        }
        $response->send();
    }

    private function buildContainer(): ContainerBuilder
    {
        $app = require $this->rootDir . '/config/app.php';
        $database = require $this->rootDir . '/config/database.php';
        $smarty = require $this->rootDir . '/config/smarty.php';
        $builder = new ContainerBuilder();
        $builder->register(Request::class, Request::class)->setFactory([Request::class, 'createFromGlobals'])->setPublic(true);
        $builder->register(Connection::class, Connection::class)->setFactory([self::class, 'createConnection'])->setArguments([$database])->setPublic(true);
        $builder->register(ValidatorInterface::class, ValidatorInterface::class)->setFactory([self::class, 'createValidator'])->setPublic(true);
        $builder->register(FilesystemAdapter::class, FilesystemAdapter::class)->setArguments(['blog', 3600, $this->rootDir . '/storage/cache'])->setPublic(true);
        $builder->setAlias(CacheInterface::class, FilesystemAdapter::class)->setPublic(true);
        $builder->register(Smarty::class, Smarty::class)->setFactory([self::class, 'createSmarty'])->setArguments([$this->rootDir, $smarty])->setPublic(true);
        $builder->register(\App\View\AssetManager::class)->setAutowired(true)->setArguments([$this->rootDir . '/public/build'])->setPublic(true);
        $builder->register(SmartyView::class)->setAutowired(true)->setPublic(true);
        $builder->register(PostgresCategoryRepository::class)->setAutowired(true)->setPublic(true);
        $builder->register(PostgresArticleRepository::class)->setAutowired(true)->setPublic(true);
        $builder->setAlias(CategoryRepositoryInterface::class, PostgresCategoryRepository::class)->setPublic(true);
        $builder->setAlias(ArticleRepositoryInterface::class, PostgresArticleRepository::class)->setPublic(true);
        foreach ([BlogService::class, HomeController::class, CategoryController::class, ArticleController::class, \App\Requests\CategoryIndexRequest::class] as $service) {
            $builder->register($service)->setAutowired(true)->setPublic(true);
        }
        $builder->compile();
        return $builder;
    }

    private static function createConnection(array $config): Connection
    {
        return DriverManager::getConnection(['dbname' => $config['database'], 'user' => $config['username'], 'password' => $config['password'], 'host' => $config['host'], 'port' => $config['port'], 'driver' => 'pdo_pgsql']);
    }

    private static function createValidator(): ValidatorInterface
    {
        return Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
    }

    private static function createSmarty(string $root, array $config): Smarty
    {
        $engine = new Smarty();
        $engine->setTemplateDir($root . '/' . $config['template_dir'])->setCompileDir($root . '/' . $config['compile_dir'])->setCacheDir($root . '/' . $config['cache_dir']);
        $engine->assign('appName', 'Simple PHP Blog');
        return $engine;
    }
}
