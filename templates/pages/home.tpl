{extends file="layouts/main.tpl"}
{block name="content"}
<section class="hero"><h1>Последние статьи</h1><p>Материалы о разработке и технологиях.</p></section>
{foreach $categories as $category}
<section class="category-section">
    <div class="section-heading"><div><h2>{$category.name|escape}</h2><p>{$category.description|escape}</p></div><a class="button" href="/category/{$category.slug|escape}">Все статьи</a></div>
    <div class="grid">{foreach $category.articles as $article}{include file="partials/article-card.tpl"}{/foreach}</div>
</section>
{/foreach}
{/block}
