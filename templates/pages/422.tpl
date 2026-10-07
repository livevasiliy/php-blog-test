{extends file="layouts/main.tpl"}
{block name="content"}<section class="hero"><h1>Ошибка валидации</h1>{foreach $errors as $field => $messages}<p><strong>{$field|escape}</strong>: {$messages|@implode:', '|escape}</p>{/foreach}</section>{/block}
