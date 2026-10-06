<?php

declare(strict_types=1);

namespace App\Validation;

use App\DTO\CategoryIndexDto;
use App\Framework\Validation\AbstractValidator;

/** @implements \App\Framework\Validation\ValidatorInterface<CategoryIndexDto> */
final class CategoryIndexValidator extends AbstractValidator
{
    private const SORTS = ['published_at', 'views_count'];
    private const DIRECTIONS = ['asc', 'desc'];
    private const MAX_PAGE = 100;

    public function validate(object $value): void
    {
        if (!$value instanceof CategoryIndexDto) {
            throw new \InvalidArgumentException('CategoryIndexValidator expects CategoryIndexDto');
        }

        $filters = $value;
        $errors = [];

        if (!in_array($filters->sort, self::SORTS, true)) {
            $errors['sort'][] = 'Допустимая сортировка: published_at или views_count.';
        }
        if (!in_array($filters->direction, self::DIRECTIONS, true)) {
            $errors['direction'][] = 'Допустимое направление: asc или desc.';
        }
        if ($filters->page < CategoryIndexDto::FIRST_PAGE || $filters->page > self::MAX_PAGE) {
            $errors['page'][] = 'Страница должна быть числом от 1 до ' . self::MAX_PAGE . '.';
        }
        $this->throwIfInvalid($errors);
    }
}
