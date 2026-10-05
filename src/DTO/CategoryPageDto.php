<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class CategoryPageDto
{
    public function __construct(
        public CategoryDto $category,
        public array $articles,
        public PaginationDto $pagination,
    ) {
    }
}
