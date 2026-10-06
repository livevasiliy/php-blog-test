<?php

declare(strict_types=1);

namespace App\Model;

use App\Framework\Model\AbstractModel;

final class Article extends AbstractModel
{
    public function __construct(
        int $id,
        public readonly string $image,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $description,
        public readonly string $content,
        public readonly int $viewsCount,
        public readonly string $publishedAt,
        public readonly array $categories = [],
    ) {
        parent::__construct($id);
    }
}
