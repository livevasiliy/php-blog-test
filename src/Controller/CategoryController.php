<?php

declare(strict_types=1);

namespace App\Controller;

use App\Requests\{CategoryIndexRequest, ValidationException};
use App\Service\BlogService;
use App\View\PhpView;
use App\Http\Response;

final class CategoryController
{
    public function __construct(private readonly BlogService $blog, private readonly PhpView $view, private readonly CategoryIndexRequest $request)
    {
    }
    public function show(string $slug): Response
    {
        try {
            $page = $this->blog->category($slug, $this->request->data());
        } catch (ValidationException $e) {
            return new Response($this->view->render('pages/422', ['errors' => $e->errors()]), 422);
        }
        return $page ? new Response($this->view->render('pages/category', ['page' => $page])) : new Response($this->view->render('pages/404'), 404);
    }
}
