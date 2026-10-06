<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\{ArticlePageDto, CategoryIndexDto, CategoryPageDto, PaginationDto};
use App\Framework\Exceptions\NotFoundException;
use App\Repository\{ArticleRepositoryInterface, CategoryRepositoryInterface};

final class BlogService
{
    public function __construct(private readonly CategoryRepositoryInterface $categories, private readonly ArticleRepositoryInterface $articles)
    {
    }

    public function home(): array
    {
        return $this->categories->withLatestArticles();
    }

    public function category(string $slug, CategoryIndexDto $filters): CategoryPageDto
    {
        $category = $this->categories->findBySlug($slug);
        if (!$category) {
            throw new NotFoundException('Category not found');
        }
        $result = $this->categories->articles($category, $filters->page, 9, $filters->sort, $filters->direction);
        return new CategoryPageDto($category, $result['items'], new PaginationDto($filters->page, 9, $result['total'], $filters->sort, $filters->direction));
    }

    public function article(string $slug): ArticlePageDto
    {
        $article = $this->articles->findBySlug($slug);
        if (!$article) {
            throw new NotFoundException('Article not found');
        }
        $this->articles->incrementViews($article->getId());
        return new ArticlePageDto($article, $this->articles->similar($article));
    }
}
