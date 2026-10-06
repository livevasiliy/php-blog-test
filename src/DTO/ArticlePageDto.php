<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\Article;

final readonly class ArticlePageDto
{
    /** @param Article[] $similarArticles */
    public function __construct(public Article $article, public array $similarArticles)
    {
    }
}
