<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Article;

interface ArticleRepositoryInterface
{
    public const DEFAULT_SIMILAR_LIMIT = 3;

    public function findBySlug(string $slug): ?Article;

    /** @return Article[] */
    public function similar(Article $article, int $limit = self::DEFAULT_SIMILAR_LIMIT): array;

    public function incrementViews(int $id): void;
}
