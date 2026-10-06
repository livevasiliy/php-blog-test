<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Category;

interface CategoryRepositoryInterface
{
    /** @return Category[] */
    public function withLatestArticles(int $limit = 3): array;

    public function findBySlug(string $slug): ?Category;

    /** @return array{items: array, total: int} */
    public function articles(Category $category, int $page, int $perPage, string $sort, string $direction): array;
}
