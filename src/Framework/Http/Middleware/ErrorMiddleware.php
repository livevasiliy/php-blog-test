<?php

declare(strict_types=1);

namespace App\Framework\Http\Middleware;

use App\Framework\Http\ExceptionResponseHandler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class ErrorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ExceptionResponseHandler $exceptions,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            return $this->exceptions->handle($exception);
        }
    }
}
