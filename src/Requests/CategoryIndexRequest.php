<?php

declare(strict_types=1);

namespace App\Requests;

use App\DTO\CategoryIndexDto;
use Psr\Http\Message\ServerRequestInterface;

final class CategoryIndexRequest
{
    public function data(ServerRequestInterface $request): CategoryIndexDto
    {
        $query = $request->getQueryParams();
        $sort = $query['sort'] ?? 'published_at';
        $direction = $query['direction'] ?? 'desc';
        $rawPage = $query['page'] ?? 1;
        $page = filter_var(is_scalar($rawPage) ? $rawPage : null, FILTER_VALIDATE_INT);

        return new CategoryIndexDto(
            is_scalar($sort) ? (string) $sort : '',
            is_scalar($direction) ? (string) $direction : '',
            $page === false ? 0 : $page,
        );
    }
}
