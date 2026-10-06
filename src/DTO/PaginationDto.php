<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class PaginationDto
{
    private const FIRST_PAGE = 1;

    public function __construct(
        public int $currentPage,
        public int $perPage,
        public int $total,
        public string $sort,
        public string $direction,
    ) {
    }

    public function totalPages(): int
    {
        return max(self::FIRST_PAGE, (int) ceil($this->total / $this->perPage));
    }
}
