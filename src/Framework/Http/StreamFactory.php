<?php

declare(strict_types=1);

namespace App\Framework\Http;

use Psr\Http\Message\StreamInterface;

final class StreamFactory
{
    public function create(string $contents = ''): StreamInterface
    {
        return new Psr7Stream($contents);
    }
}
