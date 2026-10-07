<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title|default:$appName|escape}</title>
    <link rel="stylesheet" href="{$assetCss|escape}">
</head>
<body>
<header class="site-header"><div class="container"><a class="brand" href="/">{$appName|escape}</a></div></header>
<main class="container">{block name="content"}{/block}</main>
<footer class="site-footer"><div class="container">Simple PHP MVC blog</div></footer>
</body>
</html>
