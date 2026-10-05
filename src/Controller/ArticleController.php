<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\Http\{ResponseFactory, StreamFactory};
use App\View\PhpView;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ArticleController implements RequestHandlerInterface
{
    public function __construct(
        private readonly BlogService $blog,
        private readonly PhpView $view,
        private readonly ResponseFactory $responses,
        private readonly StreamFactory $streams,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $page = $this->blog->article((string) $request->getAttribute('slug'));

        return $page
            ? $this->html($this->view->render('pages/article', ['page' => $page]))
            : $this->html($this->view->render('pages/404'), 404);
    }

    private function html(string $content, int $status = 200): ResponseInterface
    {
        return $this->responses->create($status)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withBody($this->streams->create($content));
    }
}
