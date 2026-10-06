<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\Category;

final readonly class HomePageDto
{
    /** @param Category[] $categories */
    public function __construct(public array $categories)
    {
    }
}
