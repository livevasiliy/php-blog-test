<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ArticlePageDto;
use App\DTO\CategoryIndexDto;
use App\DTO\CategoryPageDto;
use App\DTO\HomePageDto;
use App\DTO\PaginationDto;
use App\Framework\Exceptions\NotFoundException;
use App\Repository\ArticleRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Validation\CategoryIndexValidator;

final class BlogService
{
    private const ARTICLES_PER_PAGE = 9;

    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly ArticleRepositoryInterface $articles,
        private readonly CategoryIndexValidator $categoryIndexValidator,
    ) {
    }

    public function home(): HomePageDto
    {
        return new HomePageDto($this->categories->withLatestArticles());
    }

    public function category(string $slug, CategoryIndexDto $filters): CategoryPageDto
    {
        $this->categoryIndexValidator->validate($filters);
        $category = $this->categories->findBySlug($slug);
        if (!$category) {
            throw new NotFoundException('Category not found');
        }
        $result = $this->categories->articles($category, $filters->page, self::ARTICLES_PER_PAGE, $filters->sort, $filters->direction);
        return new CategoryPageDto($category, $result['items'], new PaginationDto($filters->page, self::ARTICLES_PER_PAGE, $result['total'], $filters->sort, $filters->direction));
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
