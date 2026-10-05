<?php ob_start(); ?>
<section class="hero"><h1>Последние статьи</h1><p>Материалы о разработке и технологиях.</p></section>
<?php foreach ($categories as $category): ?>
<section class="category-section"><div class="section-heading"><div><h2><?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?></h2><p><?= htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8') ?></p></div><a class="button" href="/category/<?= htmlspecialchars($category->slug, ENT_QUOTES, 'UTF-8') ?>">Все статьи</a></div>
<div class="grid"><?php foreach ($category->articles as $article): ?><?php require __DIR__ . '/../partials/article-card.php'; ?><?php endforeach; ?></div></section>
<?php endforeach; ?>
<?php $content = (string) ob_get_clean(); require __DIR__ . '/../layouts/main.php';
