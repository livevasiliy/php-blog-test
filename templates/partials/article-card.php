<article class="card">
    <img src="<?= htmlspecialchars($article->image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') ?>">
    <div class="card__body">
        <h3><a href="/article/<?= htmlspecialchars($article->slug, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') ?></a></h3>
        <p><?= htmlspecialchars($article->description, ENT_QUOTES, 'UTF-8') ?></p>
        <small><?= date('d.m.Y', strtotime($article->publishedAt)) ?> · <?= $article->viewsCount ?> просмотров</small>
    </div>
</article>
