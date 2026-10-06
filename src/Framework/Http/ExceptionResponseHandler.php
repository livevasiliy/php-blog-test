<?php

declare(strict_types=1);

namespace App\Framework\Http;

use App\Framework\Exceptions\NotFoundException;
use App\Framework\Exceptions\ValidationException;
use App\Framework\View\ViewResponseFactory;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

final class ExceptionResponseHandler
{
    public function __construct(
        private readonly ViewResponseFactory $views,
    ) {
    }

    public function handle(Throwable $exception): ResponseInterface
    {
        return match (true) {
            $exception instanceof ValidationException => $this->render('pages/422', ['errors' => $exception->errors()], HttpStatus::UNPROCESSABLE_ENTITY),
            $exception instanceof NotFoundException,
            $exception instanceof RuntimeException && $exception->getCode() === HttpStatus::NOT_FOUND => $this->render('pages/404', [], HttpStatus::NOT_FOUND),
            default => $this->render('pages/500', [], HttpStatus::INTERNAL_SERVER_ERROR),
        };
    }

    private function render(string $template, array $data, int $status): ResponseInterface
    {
        return $this->views->create($template, $data, $status);
    }
}
