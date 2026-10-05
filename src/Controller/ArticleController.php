<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\Framework\View\ViewResponseFactory;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ArticleController implements RequestHandlerInterface
{
    public function __construct(
        private readonly BlogService $blog,
        private readonly ViewResponseFactory $views,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $page = $this->blog->article((string) $request->getAttribute('slug'));
        return $this->views->create('pages/article', ['page' => $page]);
    }
}
