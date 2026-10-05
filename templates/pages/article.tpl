{extends file="layouts/main.tpl"}
{block name="content"}
<article class="article"><img class="article__image" src="{$page.article.image|escape}" alt="{$page.article.title|escape}"><h1>{$page.article.title|escape}</h1><p class="muted">{$page.article.publishedAt|date_format:'%d.%m.%Y'} · {$page.article.viewsCount + 1} просмотров</p><p class="lead">{$page.article.description|escape}</p><div class="article__content">{$page.article.content|escape|nl2br}</div></article>
{if $page.similarArticles}<section><h2>Похожие статьи</h2><div class="grid">{foreach $page.similarArticles as $article}{include file="partials/article-card.tpl"}{/foreach}</div></section>{/if}
{/block}
