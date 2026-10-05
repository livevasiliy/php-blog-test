<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CategoryDto;

interface CategoryRepositoryInterface
{
    /** @return CategoryDto[] */
    public function withLatestArticles(int $limit = 3): array;
    public function findBySlug(string $slug): ?CategoryDto;
    /** @return array{items: array, total: int} */
    public function articles(CategoryDto $category, int $page, int $perPage, string $sort, string $direction): array;
}
