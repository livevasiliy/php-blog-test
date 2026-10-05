<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class ArticlePageDto
{
    public function __construct(public ArticleDto $article, public array $similarArticles)
    {
    }
}
