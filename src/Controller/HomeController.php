<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\View\PhpView;
use App\Http\Response;

final class HomeController
{
    public function __construct(private readonly BlogService $blog, private readonly PhpView $view)
    {
    }
    public function index(): Response
    {
        return new Response($this->view->render('pages/home', ['categories' => $this->blog->home()]));
    }
}
