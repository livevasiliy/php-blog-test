<?php

declare(strict_types=1);

namespace App\Framework\Http\Middleware;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

final class MiddlewareStack implements RequestHandlerInterface
{
    private const FIRST_MIDDLEWARE_INDEX = 0;
    private const NEXT_MIDDLEWARE_OFFSET = 1;

    /** @param list<MiddlewareInterface> $middleware */
    public function __construct(
        private readonly array $middleware,
        private readonly RequestHandlerInterface $handler,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatch($request, self::FIRST_MIDDLEWARE_INDEX);
    }

    /** @internal Used by the request handler created for the next middleware. */
    public function dispatch(ServerRequestInterface $request, int $index): ResponseInterface
    {
        if (!isset($this->middleware[$index])) {
            return $this->handler->handle($request);
        }

        $next = new class ($this, $index + self::NEXT_MIDDLEWARE_OFFSET) implements RequestHandlerInterface {
            public function __construct(
                private readonly MiddlewareStack $stack,
                private readonly int $index,
            ) {
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return $this->stack->dispatch($request, $this->index);
            }
        };

        return $this->middleware[$index]->process($request, $next);
    }
}
