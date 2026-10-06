<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\Article;

final readonly class SimilarArticleDto
{
    private const NO_SHARED_CATEGORIES = 0;

    public function __construct(public Article $article, public int $sharedCategories = self::NO_SHARED_CATEGORIES)
    {
    }
}
