<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/bootstrap/bootstrap.php';

$kernel = new \App\Kernel(dirname(__DIR__));
$kernel->handle();
