<?php

declare(strict_types=1);

namespace App\Requests;

use App\DTO\CategoryIndexDto;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;

final class CategoryIndexRequest
{
    private const MAX_PAGE = 100;

    public function data(ServerRequestInterface $request): array
    {
        $query = $request->getQueryParams();
        $sort = (string) ($query['sort'] ?? 'published_at');
        $direction = (string) ($query['direction'] ?? 'desc');
        $rawPage = $query['page'] ?? 1;
        $page = filter_var(is_scalar($rawPage) ? $rawPage : null, FILTER_VALIDATE_INT);
        $errors = [];
        if (!in_array($sort, ['published_at', 'views_count'], true)) {
            $errors['sort'][] = 'Допустимая сортировка: published_at или views_count.';
        }
        if (!in_array($direction, ['asc', 'desc'], true)) {
            $errors['direction'][] = 'Допустимое направление: asc или desc.';
        }
        if ($page === false || $page < 1 || $page > self::MAX_PAGE) {
            $errors['page'][] = 'Страница должна быть числом от 1 до ' . self::MAX_PAGE . '.';
        }
        if ($errors) {
            throw new ValidationException($errors);
        }
        return ['sort' => $sort, 'direction' => $direction, 'page' => $page];
    }
}
