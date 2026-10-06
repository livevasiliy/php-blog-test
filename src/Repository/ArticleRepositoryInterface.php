<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Article;

interface ArticleRepositoryInterface
{
    public function findBySlug(string $slug): ?Article;

    /** @return Article[] */
    public function similar(Article $article, int $limit = 3): array;

    public function incrementViews(int $id): void;
}
