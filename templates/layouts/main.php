<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? $appName, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($assetCss, ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
<header class="site-header"><div class="container"><a class="brand" href="/"><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></a></div></header>
<main class="container"><?= $content ?></main>
<footer class="site-footer"><div class="container">Plain PHP MVC blog</div></footer>
</body>
</html>
