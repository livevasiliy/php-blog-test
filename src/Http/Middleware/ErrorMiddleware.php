<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\{ResponseFactory, StreamFactory};
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Throwable;

final class ErrorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactory $responses,
        private readonly StreamFactory $streams,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            $status = $exception->getCode() === 404 ? 404 : 500;
            $body = $status === 404 ? '<h1>404 Not Found</h1>' : '<h1>500 Internal Server Error</h1>';

            return $this->responses->create($status)
                ->withHeader('Content-Type', 'text/html; charset=UTF-8')
                ->withBody($this->streams->create($body));
        }
    }
}
