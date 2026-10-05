<?php

declare(strict_types=1);

return [
    'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int) ($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 5432),
    'database' => $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: 'blog',
    'username' => $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: 'blog',
    'password' => $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: 'blog',
];
