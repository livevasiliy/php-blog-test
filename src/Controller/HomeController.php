<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\View\SmartyView;
use Symfony\Component\HttpFoundation\Response;

final class HomeController
{
    public function __construct(private readonly BlogService $blog, private readonly SmartyView $view)
    {
    }
    public function index(): Response
    {
        return new Response($this->view->render('pages/home.tpl', ['categories' => $this->blog->home()]));
    }
}
