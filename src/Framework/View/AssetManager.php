<?php

declare(strict_types=1);

namespace App\Framework\View;

final class AssetManager
{
    private ?string $css = null;

    public function __construct(private readonly string $buildDir)
    {
    }

    public function css(): string
    {
        return $this->css ??= (function (): string {
            $manifest = $this->buildDir . '/.vite/manifest.json';
            if (is_file($manifest)) {
                $data = json_decode((string) file_get_contents($manifest), true);
                return '/build/' . ($data['resources/scss/app.scss']['file'] ?? 'assets/app.css');
            }
            return '/assets/css/app.css';
        })();
    }
}
