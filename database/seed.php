<?php

declare(strict_types=1);
require dirname(__DIR__) . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
$config = require dirname(__DIR__) . '/config/database.php';
$db = Doctrine\DBAL\DriverManager::getConnection(['dbname' => $config['database'], 'user' => $config['username'], 'password' => $config['password'], 'host' => $config['host'], 'port' => $config['port'], 'driver' => 'pdo_pgsql']);
$categories = (new Database\Seeders\CategorySeeder($db))->run();
(new Database\Seeders\ArticleSeeder($db))->run($categories);
echo "Database seeded successfully.\n";
