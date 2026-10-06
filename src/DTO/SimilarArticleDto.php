<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\Article;

final readonly class SimilarArticleDto
{
    public function __construct(public Article $article, public int $sharedCategories = 0)
    {
    }
}
