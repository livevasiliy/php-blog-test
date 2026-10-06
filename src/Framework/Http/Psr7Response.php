<?php

declare(strict_types=1);

namespace App\Framework\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class Psr7Response extends Psr7Message implements ResponseInterface
{
    private int $status;
    private string $reason;

    public function __construct(int $status = HttpStatus::OK, array $headers = [], ?StreamInterface $body = null, string $reason = '')
    {
        parent::__construct($body, $headers);
        $this->status = $status;
        $this->reason = $reason;
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $clone = clone $this;
        $clone->status = $code;
        $clone->reason = $reasonPhrase;
        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reason;
    }
}
