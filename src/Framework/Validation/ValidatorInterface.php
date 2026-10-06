<?php

declare(strict_types=1);

namespace App\Framework\Validation;

/**
 * @template TObject of object
 */
interface ValidatorInterface
{
    /**
     * @param TObject $value
     */
    public function validate(object $value): void;
}
