<?php

declare(strict_types=1);

namespace App\Framework\Http;

use Psr\Http\Message\ServerRequestInterface;

final class ServerRequestFactory
{
    public function fromGlobals(): ServerRequestInterface
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return new Psr7ServerRequest(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            new Psr7Uri($uri),
            $_SERVER,
            $this->headers(),
            $_COOKIE,
            $_GET,
            $_FILES,
            $_POST ?: null,
        );
    }

    private function headers(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }
}
