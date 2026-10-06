<?php

declare(strict_types=1);

namespace App\Model;

use App\Framework\Model\AbstractModel;

final class Category extends AbstractModel
{
    /** @param Article[] $articles */
    public function __construct(
        int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $description,
        public readonly array $articles = [],
    ) {
        parent::__construct($id);
    }
}
