<?php

declare(strict_types=1);

namespace App\Framework\Http;

use Psr\Http\Message\ResponseInterface;

final class HtmlResponseFactory
{
    public function __construct(
        private readonly ResponseFactory $responses,
        private readonly StreamFactory $streams,
    ) {
    }

    public function create(string $content, int $status = 200): ResponseInterface
    {
        return $this->responses->create($status)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withBody($this->streams->create($content));
    }
}
