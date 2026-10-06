<?php

declare(strict_types=1);

namespace App\Framework\Model;

abstract class AbstractModel implements ModelInterface
{
    public function __construct(public readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
