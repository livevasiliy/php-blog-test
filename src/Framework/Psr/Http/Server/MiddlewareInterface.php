<?php

namespace Psr\Http\Server;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

interface MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
