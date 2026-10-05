<?php

declare(strict_types=1);

namespace App\Framework\View;

use RuntimeException;

final class PhpView
{
    public function __construct(private readonly string $templateDir, private readonly AssetManager $assets)
    {
    }

    public function render(string $template, array $data = []): string
    {
        $file = $this->templateDir . '/' . $template . '.php';
        if (!is_file($file)) {
            throw new RuntimeException("Template not found: {$template}");
        }
        extract(array_merge(['assetCss' => $this->assets->css(), 'appName' => 'Simple PHP Blog'], $data));
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
