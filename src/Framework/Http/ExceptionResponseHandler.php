<?php

declare(strict_types=1);

namespace App\Framework\Http;

use App\Framework\Exceptions\{NotFoundException, ValidationException};
use App\Framework\View\PhpView;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

final class ExceptionResponseHandler
{
    public function __construct(
        private readonly HtmlResponseFactory $html,
        private readonly PhpView $view,
    ) {
    }

    public function handle(Throwable $exception): ResponseInterface
    {
        return match (true) {
            $exception instanceof ValidationException => $this->render('pages/422', ['errors' => $exception->errors()], 422),
            $exception instanceof NotFoundException,
            $exception instanceof RuntimeException && $exception->getCode() === 404 => $this->render('pages/404', [], 404),
            default => $this->render('pages/500', [], 500),
        };
    }

    private function render(string $template, array $data, int $status): ResponseInterface
    {
        return $this->html->create($this->view->render($template, $data), $status);
    }
}
