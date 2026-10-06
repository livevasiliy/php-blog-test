<?php

declare(strict_types=1);

namespace App\Framework\Validation;

use App\Framework\Exceptions\ValidationException;

abstract class AbstractValidator implements ValidatorInterface
{
    /** @param array<string, string[]> $errors */
    protected function throwIfInvalid(array $errors): void
    {
        if ($errors) {
            throw new ValidationException($errors);
        }
    }
}
