<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\{ResponseInterface, StreamInterface};

final class ResponseFactory
{
    public function create(int $status = 200, array $headers = [], ?StreamInterface $body = null): ResponseInterface
    {
        return new Psr7Response($status, $headers, $body);
    }
}
