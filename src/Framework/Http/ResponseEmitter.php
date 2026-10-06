<?php

declare(strict_types=1);

namespace App\Framework\Http;

use Psr\Http\Message\ResponseInterface;

final class ResponseEmitter
{
    private const READ_CHUNK_SIZE = 8192;

    public function emit(ResponseInterface $response): void
    {
        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, false);
            }
        }

        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }
        while (!$body->eof()) {
            echo $body->read(self::READ_CHUNK_SIZE);
        }
    }
}
