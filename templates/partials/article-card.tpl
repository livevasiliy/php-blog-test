<article class="card">
    <img src="{$article.image|escape}" alt="{$article.title|escape}">
    <div class="card__body">
        <h3><a href="/article/{$article.slug|escape}">{$article.title|escape}</a></h3>
        <p>{$article.description|escape}</p>
        <small>{$article.publishedAt|date_format:'%d.%m.%Y'} · {$article.viewsCount} просмотров</small>
    </div>
</article>
