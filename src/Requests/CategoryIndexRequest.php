<?php

declare(strict_types=1);

namespace App\Requests;

use App\DTO\CategoryIndexDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CategoryIndexRequest
{
    public function __construct(private readonly Request $httpRequest, private readonly ValidatorInterface $validator)
    {
    }
    public function data(): array
    {
        $dto = new CategoryIndexDto(
            (string) $this->httpRequest->query->get('sort', 'published_at'),
            (string) $this->httpRequest->query->get('direction', 'desc'),
            (int) $this->httpRequest->query->get('page', 1),
        );
        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            throw new ValidationException($errors);
        }
        return ['sort' => $dto->sort, 'direction' => $dto->direction, 'page' => $dto->page];
    }
}
