<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class CategoryDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $description,
        public array $articles = [],
    ) {
    }
}
