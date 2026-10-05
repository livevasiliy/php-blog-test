<?php

declare(strict_types=1);

namespace App\Config;

function env(string $name, mixed $default = null): mixed
{
    $value = $_ENV[$name] ?? getenv($name);

    return $value === false || $value === null ? $default : $value;
}

function requiredEnv(string $name): string
{
    $value = env($name);

    if (!is_string($value) || trim($value) === '') {
        throw new \RuntimeException($name . ' must be configured');
    }

    return $value;
}
