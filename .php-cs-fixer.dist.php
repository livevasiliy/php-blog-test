<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/routes',
        __DIR__ . '/database',
        __DIR__ . '/public',
        __DIR__ . '/bin',
    ])
    ->exclude(['storage']);

return (new Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12' => true,
        'class_attributes_separation' => [
            'elements' => ['method' => 'one'],
        ],
    ])
    ->setFinder($finder);
