<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\{RequestInterface, ServerRequestInterface, UriInterface};

final class Psr7ServerRequest extends Psr7Message implements ServerRequestInterface
{
    private string $method;
    private UriInterface $uri;
    private string $target = '';
    private array $server;
    private array $cookies;
    private array $query;
    private array $uploaded;
    private mixed $parsedBody;
    private array $attributes;

    public function __construct(string $method, UriInterface $uri, array $server = [], array $headers = [], array $cookies = [], array $query = [], array $uploaded = [], mixed $parsedBody = null, array $attributes = [])
    {
        parent::__construct(null, $headers);
        $this->method = $method; $this->uri = $uri; $this->server = $server; $this->cookies = $cookies; $this->query = $query; $this->uploaded = $uploaded; $this->parsedBody = $parsedBody; $this->attributes = $attributes;
    }
    public function getRequestTarget(): string { return $this->target ?: (($this->uri->getPath() ?: '/') . ($this->uri->getQuery() ? '?' . $this->uri->getQuery() : '')); }
    public function withRequestTarget(string $requestTarget): RequestInterface { $clone = clone $this; $clone->target = $requestTarget; return $clone; }
    public function getMethod(): string { return $this->method; }
    public function withMethod(string $method): RequestInterface { $clone = clone $this; $clone->method = $method; return $clone; }
    public function getUri(): UriInterface { return $this->uri; }
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface { $clone = clone $this; $clone->uri = $uri; return $clone; }
    public function getServerParams(): array { return $this->server; }
    public function getCookieParams(): array { return $this->cookies; }
    public function withCookieParams(array $cookies): ServerRequestInterface { $clone = clone $this; $clone->cookies = $cookies; return $clone; }
    public function getQueryParams(): array { return $this->query; }
    public function withQueryParams(array $query): ServerRequestInterface { $clone = clone $this; $clone->query = $query; return $clone; }
    public function getUploadedFiles(): array { return $this->uploaded; }
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface { $clone = clone $this; $clone->uploaded = $uploadedFiles; return $clone; }
    public function getParsedBody() { return $this->parsedBody; }
    public function withParsedBody($data): ServerRequestInterface { $clone = clone $this; $clone->parsedBody = $data; return $clone; }
    public function getAttributes(): array { return $this->attributes; }
    public function getAttribute(string $name, $default = null) { return $this->attributes[$name] ?? $default; }
    public function withAttribute(string $name, $value): ServerRequestInterface { $clone = clone $this; $clone->attributes[$name] = $value; return $clone; }
    public function withoutAttribute(string $name): ServerRequestInterface { $clone = clone $this; unset($clone->attributes[$name]); return $clone; }
}
