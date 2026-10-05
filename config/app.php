<?php

declare(strict_types=1);

return [
    'name' => 'Simple PHP Blog',
    'env' => $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: 'local',
    'url' => $_ENV['APP_URL'] ?? getenv('APP_URL') ?: 'http://localhost:8080',
];
