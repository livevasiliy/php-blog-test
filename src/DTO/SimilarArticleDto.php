<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class SimilarArticleDto
{
    public function __construct(public ArticleDto $article, public int $sharedCategories = 0)
    {
    }
}
