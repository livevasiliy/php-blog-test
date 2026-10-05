<?php

declare(strict_types=1);

use function App\Config\env;
use function App\Config\requiredEnv;

return [
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => (int) env('DB_PORT', 5432),
    'database' => env('DB_DATABASE', 'blog'),
    'username' => requiredEnv('DB_USERNAME'),
    'password' => requiredEnv('DB_PASSWORD'),
];
