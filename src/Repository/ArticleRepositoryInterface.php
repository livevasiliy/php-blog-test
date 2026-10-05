<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\ArticleDto;

interface ArticleRepositoryInterface
{
    public function findBySlug(string $slug): ?ArticleDto;
    /** @return ArticleDto[] */
    public function similar(ArticleDto $article, int $limit = 3): array;
    public function incrementViews(int $id): void;
}
