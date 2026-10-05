<?php

declare(strict_types=1);

use App\Controller\{ArticleController, CategoryController, HomeController};
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('home', new Route('/', ['_controller' => [HomeController::class, 'index']], methods: ['GET']));
$routes->add('category', new Route('/category/{slug}', ['_controller' => [CategoryController::class, 'show']], methods: ['GET']));
$routes->add('article', new Route('/article/{slug}', ['_controller' => [ArticleController::class, 'show']], methods: ['GET']));
return $routes;
