<?php

declare(strict_types=1);

namespace App\DTO;

use App\Framework\Pagination\PaginationDto;
use App\Model\Category;

final readonly class CategoryPageDto
{
    public function __construct(
        public Category $category,
        public array $articles,
        public PaginationDto $pagination,
    ) {
    }
}
