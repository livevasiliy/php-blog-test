<?php

declare(strict_types=1);

namespace App\Framework\Http;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

final class Psr7Stream implements StreamInterface
{
    /** @var resource|null */
    private $resource;

    public function __construct(string $contents = '')
    {
        $this->resource = fopen('php://temp', 'r+');
        $this->write($contents);
        $this->rewind();
    }

    public function __toString(): string
    {
        try {
            $this->rewind();
            return $this->getContents();
        } catch (RuntimeException) {
            return '';
        }
    }

    public function close(): void
    {
        if (is_resource($this->resource)) {
            fclose($this->resource);
        } $this->resource = null;
    }

    public function detach()
    {
        $resource = $this->resource;
        $this->resource = null;
        return $resource;
    }

    public function getSize(): ?int
    {
        return is_resource($this->resource) ? fstat($this->resource)['size'] : null;
    }

    public function tell(): int
    {
        return $this->resource ? ftell($this->resource) : throw new RuntimeException('Stream is detached');
    }

    public function eof(): bool
    {
        return !$this->resource || feof($this->resource);
    }

    public function isSeekable(): bool
    {
        return (bool) ($this->resource && stream_get_meta_data($this->resource)['seekable']);
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->resource || fseek($this->resource, $offset, $whence) !== 0) {
            throw new RuntimeException('Unable to seek stream');
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        return (bool) ($this->resource && strpbrk(stream_get_meta_data($this->resource)['mode'], 'waxc+') !== false);
    }

    public function write(string $string): int
    {
        if (!$this->resource || !$this->isWritable()) {
            throw new RuntimeException('Stream is not writable');
        } return fwrite($this->resource, $string);
    }

    public function isReadable(): bool
    {
        return (bool) ($this->resource && strpbrk(stream_get_meta_data($this->resource)['mode'], 'r+') !== false);
    }

    public function read(int $length): string
    {
        if (!$this->resource || !$this->isReadable()) {
            throw new RuntimeException('Stream is not readable');
        } return fread($this->resource, $length);
    }

    public function getContents(): string
    {
        if (!$this->resource || !$this->isReadable()) {
            throw new RuntimeException('Stream is not readable');
        } return stream_get_contents($this->resource);
    }

    public function getMetadata(?string $key = null)
    {
        if (!$this->resource) {
            return $key === null ? [] : null;
        } $metadata = stream_get_meta_data($this->resource);
        return $key === null ? $metadata : ($metadata[$key] ?? null);
    }
}
