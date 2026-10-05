<?php ob_start(); ?><section class="hero"><h1>Страница не найдена</h1><a href="/">На главную</a></section><?php $content = (string) ob_get_clean(); require __DIR__ . '/../layouts/main.php';
