<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\ValidationException;
use App\Http\{ResponseFactory, StreamFactory};
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
        private readonly ResponseFactory $responses,
        private readonly StreamFactory $streams,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $page = $this->blog->category((string) $request->getAttribute('slug'), $this->request->data($request));
        } catch (ValidationException $e) {
            return $this->html($this->view->render('pages/422', ['errors' => $e->errors()]), 422);
        }

        return $page
            ? $this->html($this->view->render('pages/category', ['page' => $page]))
            : $this->html($this->view->render('pages/404'), 404);
    }

    private function html(string $content, int $status = 200): ResponseInterface
    {
        return $this->responses->create($status)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withBody($this->streams->create($content));
    }
}
