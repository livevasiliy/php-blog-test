<?php

declare(strict_types=1);

namespace App\Framework\Exceptions;

use App\Framework\Http\HttpStatus;
use RuntimeException;

final class NotFoundException extends RuntimeException
{
    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct($message, HttpStatus::NOT_FOUND);
    }
}
