<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\NotFoundException;
use App\Http\HtmlResponseFactory;
use App\Requests\CategoryIndexRequest;
use App\Service\BlogService;
use App\View\PhpView;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategoryController implements RequestHandlerInterface
{
    public function __construct(
        private readonly BlogService $blog,
        private readonly PhpView $view,
        private readonly CategoryIndexRequest $request,
        private readonly HtmlResponseFactory $html,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $page = $this->blog->category((string) $request->getAttribute('slug'), $this->request->data($request));
        if (!$page) {
            throw new NotFoundException('Category not found');
        }

        return $this->html->create($this->view->render('pages/category', ['page' => $page]));
    }
}
