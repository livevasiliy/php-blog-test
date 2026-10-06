<?php

declare(strict_types=1);

namespace App\Controller;

use App\Requests\CategoryIndexRequest;
use App\Service\BlogService;
use App\Framework\View\ViewResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CategoryController implements RequestHandlerInterface
{
    public function __construct(
        private readonly BlogService $blog,
        private readonly CategoryIndexRequest $request,
        private readonly ViewResponseFactory $views,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $page = $this->blog->category((string) $request->getAttribute('slug'), $this->request->data($request));
        return $this->views->create('pages/category', ['page' => $page]);
    }
}
