<?php

declare(strict_types=1);

namespace App\Requests;

use App\DTO\CategoryIndexDto;
use App\Http\Request;

final class CategoryIndexRequest
{
    public function __construct(private readonly Request $httpRequest)
    {
    }
    public function data(): array
    {
        $sort = (string) $this->httpRequest->query('sort', 'published_at');
        $direction = (string) $this->httpRequest->query('direction', 'desc');
        $page = filter_var($this->httpRequest->query('page', 1), FILTER_VALIDATE_INT);
        $errors = [];
        if (!in_array($sort, ['published_at', 'views_count'], true)) {
            $errors['sort'][] = 'Допустимая сортировка: published_at или views_count.';
        }
        if (!in_array($direction, ['asc', 'desc'], true)) {
            $errors['direction'][] = 'Допустимое направление: asc или desc.';
        }
        if ($page === false || $page < 1) {
            $errors['page'][] = 'Страница должна быть положительным числом.';
        }
        if ($errors) {
            throw new ValidationException($errors);
        }
        return ['sort' => $sort, 'direction' => $direction, 'page' => $page];
    }
}
