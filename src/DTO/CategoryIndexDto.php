<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class CategoryIndexDto
{
    public const FIRST_PAGE = 1;

    public function __construct(
        public string $sort = 'published_at',
        public string $direction = 'desc',
        public int $page = self::FIRST_PAGE,
    ) {
    }
}
