<?php

declare(strict_types=1);

$required = static function (string $name): string {
    $value = $_ENV[$name] ?? getenv($name);
    if (!is_string($value) || trim($value) === '') {
        throw new RuntimeException($name . ' must be configured');
    }
    return $value;
};

return [
    'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int) ($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 5432),
    'database' => $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: 'blog',
    'username' => $required('DB_USERNAME'),
    'password' => $required('DB_PASSWORD'),
];
