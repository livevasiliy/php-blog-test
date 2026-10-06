<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Category;

interface CategoryRepositoryInterface
{
    public const DEFAULT_LATEST_LIMIT = 3;

    /** @return Category[] */
    public function withLatestArticles(int $limit = self::DEFAULT_LATEST_LIMIT): array;

    public function findBySlug(string $slug): ?Category;

    /** @return array{items: array, total: int} */
    public function articles(Category $category, int $page, int $perPage, string $sort, string $direction): array;
}
