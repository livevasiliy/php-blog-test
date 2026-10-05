<?php

declare(strict_types=1);

namespace App\View;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class AssetManager
{
    public function __construct(private readonly string $buildDir, private readonly CacheInterface $cache)
    {
    }
    public function css(): string
    {
        return $this->cache->get('app-css', function (ItemInterface $item): string {
            $item->expiresAfter(300);
            $manifest = $this->buildDir . '/.vite/manifest.json';
            if (is_file($manifest)) {
                $data = json_decode((string) file_get_contents($manifest), true);
                return '/build/' . ($data['resources/scss/app.scss']['file'] ?? 'assets/app.css');
            }
            return '/assets/css/app.css';
        });
    }
}
