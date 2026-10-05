<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\UriInterface;

final class Psr7Uri implements UriInterface
{
    private string $scheme = '';
    private string $userInfo = '';
    private string $host = '';
    private ?int $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function __construct(string $uri = '')
    {
        $parts = parse_url($uri) ?: [];
        $this->scheme = strtolower($parts['scheme'] ?? '');
        $this->userInfo = isset($parts['user']) ? $parts['user'] . (isset($parts['pass']) ? ':' . $parts['pass'] : '') : '';
        $this->host = strtolower($parts['host'] ?? '');
        $this->port = $parts['port'] ?? null;
        $this->path = $parts['path'] ?? '';
        $this->query = $parts['query'] ?? '';
        $this->fragment = $parts['fragment'] ?? '';
    }
    public function getScheme(): string { return $this->scheme; }
    public function getAuthority(): string { return ($this->userInfo ? $this->userInfo . '@' : '') . $this->host . ($this->port === null ? '' : ':' . $this->port); }
    public function getUserInfo(): string { return $this->userInfo; }
    public function getHost(): string { return $this->host; }
    public function getPort(): ?int { return $this->port; }
    public function getPath(): string { return $this->path; }
    public function getQuery(): string { return $this->query; }
    public function getFragment(): string { return $this->fragment; }
    public function withScheme(string $scheme): UriInterface { $clone = clone $this; $clone->scheme = strtolower($scheme); return $clone; }
    public function withUserInfo(string $user, ?string $password = null): UriInterface { $clone = clone $this; $clone->userInfo = $user . ($password === null ? '' : ':' . $password); return $clone; }
    public function withHost(string $host): UriInterface { $clone = clone $this; $clone->host = strtolower($host); return $clone; }
    public function withPort(?int $port): UriInterface { $clone = clone $this; $clone->port = $port; return $clone; }
    public function withPath(string $path): UriInterface { $clone = clone $this; $clone->path = $path; return $clone; }
    public function withQuery(string $query): UriInterface { $clone = clone $this; $clone->query = ltrim($query, '?'); return $clone; }
    public function withFragment(string $fragment): UriInterface { $clone = clone $this; $clone->fragment = ltrim($fragment, '#'); return $clone; }
    public function __toString(): string { return ($this->scheme ? $this->scheme . '://' : '') . ($this->getAuthority()) . $this->path . ($this->query ? '?' . $this->query : '') . ($this->fragment ? '#' . $this->fragment : ''); }
}
