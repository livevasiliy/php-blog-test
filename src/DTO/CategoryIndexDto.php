<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CategoryIndexDto
{
    public function __construct(
        #[Assert\Choice(choices: ['published_at', 'views_count'])]
        public string $sort = 'published_at',
        #[Assert\Choice(choices: ['asc', 'desc'])]
        public string $direction = 'desc',
        #[Assert\Positive]
        public int $page = 1,
    ) {
    }
}
