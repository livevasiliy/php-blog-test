<?php

declare(strict_types=1);

use App\Controller\{ArticleController, CategoryController, HomeController};
use App\Framework\DI\Container;
use App\Framework\Http\Router;

return static function (Router $router, Container $container): void {
    $router->get('/', $container->get(HomeController::class));
    $router->get('/category/{slug}', $container->get(CategoryController::class));
    $router->get('/article/{slug}', $container->get(ArticleController::class));
};
