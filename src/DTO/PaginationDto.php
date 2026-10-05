<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class PaginationDto
{
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
        return max(1, (int) ceil($this->total / $this->perPage));
    }
}
