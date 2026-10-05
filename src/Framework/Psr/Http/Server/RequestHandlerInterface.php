<?php

namespace Psr\Http\Server;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

interface RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
