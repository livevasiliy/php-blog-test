<?php

declare(strict_types=1);

namespace App\Validation;

use App\DTO\CategoryIndexDto;
use App\Framework\Exceptions\ValidationException;

final class CategoryIndexValidator
{
    private const SORTS = ['published_at', 'views_count'];
    private const DIRECTIONS = ['asc', 'desc'];
    private const MAX_PAGE = 100;

    public function validate(CategoryIndexDto $filters): void
    {
        $errors = [];

        if (!in_array($filters->sort, self::SORTS, true)) {
            $errors['sort'][] = 'Допустимая сортировка: published_at или views_count.';
        }
        if (!in_array($filters->direction, self::DIRECTIONS, true)) {
            $errors['direction'][] = 'Допустимое направление: asc или desc.';
        }
        if ($filters->page < 1 || $filters->page > self::MAX_PAGE) {
            $errors['page'][] = 'Страница должна быть числом от 1 до ' . self::MAX_PAGE . '.';
        }
        if ($errors) {
            throw new ValidationException($errors);
        }
    }
}
