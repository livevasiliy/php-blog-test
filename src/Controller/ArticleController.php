<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\Http\HtmlResponseFactory;
use App\View\PhpView;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ArticleController implements RequestHandlerInterface
{
    public function __construct(
        private readonly BlogService $blog,
        private readonly PhpView $view,
        private readonly HtmlResponseFactory $html,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $page = $this->blog->article((string) $request->getAttribute('slug'));

        return $page
            ? $this->html->create($this->view->render('pages/article', ['page' => $page]))
            : $this->html->create($this->view->render('pages/404'), 404);
    }
}
