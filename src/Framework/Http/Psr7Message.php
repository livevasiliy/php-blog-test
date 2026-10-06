<?php

declare(strict_types=1);

namespace App\Framework\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class Psr7Message implements MessageInterface
{
    private const DEFAULT_PROTOCOL_VERSION = '1.1';

    protected string $protocol = self::DEFAULT_PROTOCOL_VERSION;
    /** @var array<string, list<string>> */
    protected array $headers = [];
    protected StreamInterface $body;

    public function __construct(?StreamInterface $body = null, array $headers = [])
    {
        $this->body = $body ?? new Psr7Stream();
        foreach ($headers as $name => $value) {
            $this->headers[$name] = array_map('strval', (array) $value);
        }
    }

    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $clone = clone $this;
        $clone->protocol = $version;
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return $this->headerName($name) !== null;
    }

    public function getHeader(string $name): array
    {
        $key = $this->headerName($name);
        return $key === null ? [] : $this->headers[$key];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $clone = clone $this;
        $key = $clone->headerName($name) ?? $name;
        $clone->headers[$key] = array_map('strval', (array) $value);
        return $clone;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $clone = clone $this;
        $key = $clone->headerName($name) ?? $name;
        $clone->headers[$key] = array_merge($clone->headers[$key] ?? [], array_map('strval', (array) $value));
        return $clone;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $clone = clone $this;
        $key = $clone->headerName($name);
        if ($key !== null) {
            unset($clone->headers[$key]);
        } return $clone;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    private function headerName(string $name): ?string
    {
        foreach (array_keys($this->headers) as $key) {
            if (strcasecmp($key, $name) === 0) {
                return $key;
            }
        } return null;
    }
}
