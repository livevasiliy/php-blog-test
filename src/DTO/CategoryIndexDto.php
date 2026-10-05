<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class CategoryIndexDto
{
    public function __construct(
        public string $sort = 'published_at',
        public string $direction = 'desc',
        public int $page = 1,
    ) {
    }
}
