<?php

declare(strict_types=1);

namespace App\Framework\View;

use Smarty;

final class SmartyView
{
    public function __construct(
        private readonly Smarty $smarty,
        private readonly AssetManager $assets,
    ) {
    }

    public function render(string $template, array $data = []): string
    {
        return $this->smarty
            ->assign(array_merge([
                'assetCss' => $this->assets->css(),
                'appName' => 'Simple PHP Blog',
            ], $data))
            ->fetch($template . '.tpl');
    }
}
