<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class ArticleDto
{
    public function __construct(
        public int $id,
        public string $image,
        public string $title,
        public string $slug,
        public string $description,
        public string $content,
        public int $viewsCount,
        public string $publishedAt,
        public array $categories = [],
    ) {
    }
}
