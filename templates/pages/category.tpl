{extends file="layouts/main.tpl"}
{block name="content"}
<section class="hero"><h1>{$page.category.name|escape}</h1><p>{$page.category.description|escape}</p></section>
<form class="filters" method="get">
    <label>Сортировка <select name="sort"><option value="published_at"{if $page.pagination.sort === 'published_at'} selected{/if}>По дате</option><option value="views_count"{if $page.pagination.sort === 'views_count'} selected{/if}>По просмотрам</option></select></label>
    <label>Направление <select name="direction"><option value="desc"{if $page.pagination.direction === 'desc'} selected{/if}>По убыванию</option><option value="asc"{if $page.pagination.direction === 'asc'} selected{/if}>По возрастанию</option></select></label>
    <button class="button" type="submit">Применить</button>
</form>
<div class="grid">{foreach $page.articles as $article}{include file="partials/article-card.tpl"}{foreachelse}<p>В этой категории пока нет статей.</p>{/foreach}</div>
{if $page.pagination.totalPages() > 1}<nav class="pagination">{for $p=1 to $page.pagination.totalPages()}<a class="{if $p === $page.pagination.currentPage}active{/if}" href="?sort={$page.pagination.sort|escape}&direction={$page.pagination.direction|escape}&page={$p}">{$p}</a>{/for}</nav>{/if}
{/block}
