<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\Framework\Http\HtmlResponseFactory;
use App\View\PhpView;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class HomeController implements RequestHandlerInterface
{
    public function __construct(
        private readonly BlogService $blog,
        private readonly PhpView $view,
        private readonly HtmlResponseFactory $html,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->html->create($this->view->render('pages/home', ['categories' => $this->blog->home()]));
    }
}
