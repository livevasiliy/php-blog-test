<?php

declare(strict_types=1);

namespace App\Framework\View;

use App\Framework\Http\HtmlResponseFactory;
use Psr\Http\Message\ResponseInterface;

final class ViewResponseFactory
{
    public function __construct(
        private readonly PhpView $view,
        private readonly HtmlResponseFactory $html,
    ) {
    }

    public function create(string $template, array $data = [], int $status = 200): ResponseInterface
    {
        return $this->html->create($this->view->render($template, $data), $status);
    }
}
