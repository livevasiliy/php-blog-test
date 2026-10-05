<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\Http\{ResponseFactory, StreamFactory};
use App\View\PhpView;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class HomeController implements RequestHandlerInterface
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
        return $this->html($this->view->render('pages/home', ['categories' => $this->blog->home()]));
    }

    private function html(string $content): ResponseInterface
    {
        return $this->responses->create()
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withBody($this->streams->create($content));
    }
}
