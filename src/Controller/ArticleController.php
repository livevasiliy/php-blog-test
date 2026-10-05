<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\View\SmartyView;
use Symfony\Component\HttpFoundation\Response;

final class ArticleController
{
    public function __construct(private readonly BlogService $blog, private readonly SmartyView $view)
    {
    }
    public function show(string $slug): Response
    {
        $page = $this->blog->article($slug);
        return $page ? new Response($this->view->render('pages/article.tpl', ['page' => $page])) : new Response($this->view->render('pages/404.tpl'), 404);
    }
}
