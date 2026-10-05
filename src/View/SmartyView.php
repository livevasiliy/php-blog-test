<?php

declare(strict_types=1);

namespace App\View;

use Smarty;

final class SmartyView
{
    public function __construct(private readonly Smarty $smarty, private readonly AssetManager $assets)
    {
    }
    public function render(string $template, array $data = []): string
    {
        return $this->smarty->assign(array_merge(['assetCss' => $this->assets->css()], $data))->fetch($template);
    }
}
