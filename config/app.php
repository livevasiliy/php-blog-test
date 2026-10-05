<?php

declare(strict_types=1);

use function App\Config\env;

return [
    'name' => 'Simple PHP Blog',
    'env' => env('APP_ENV', 'local'),
    'url' => env('APP_URL', 'http://localhost:8080'),
];
