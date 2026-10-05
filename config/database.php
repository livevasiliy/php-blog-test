<?php

declare(strict_types=1);

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int) (getenv('DB_PORT') ?: 3306),
    'database' => getenv('DB_DATABASE') ?: 'blog',
    'username' => getenv('DB_USERNAME') ?: 'blog',
    'password' => getenv('DB_PASSWORD') ?: 'blog',
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
];
