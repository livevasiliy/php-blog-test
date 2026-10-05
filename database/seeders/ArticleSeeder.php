<?php

declare(strict_types=1);

namespace Database\Seeders;

use PDO;

final class ArticleSeeder
{
    public function __construct(private readonly PDO $db)
    {
    }

    public function run(array $categories): void
    {
        $articles = [['first-steps-php', 'Первые шаги в PHP', 120, ['php']], ['pdo-and-db', 'Надёжная работа с PDO', 95, ['php', 'databases']], ['sql-indexes', 'Зачем нужны SQL-индексы', 240, ['databases']], ['docker-for-php', 'Docker для PHP-проекта', 180, ['devops', 'php']], ['clean-config', 'Конфигурация приложения', 80, ['php']]];
        $articleStatement = $this->db->prepare('INSERT INTO articles (image, title, slug, description, content, views_count, published_at) VALUES (:image, :title, :slug, :description, :content, :views, :published) ON CONFLICT (slug) DO UPDATE SET title = EXCLUDED.title, description = EXCLUDED.description, content = EXCLUDED.content, views_count = EXCLUDED.views_count, published_at = EXCLUDED.published_at');
        $findArticle = $this->db->prepare('SELECT id FROM articles WHERE slug = :slug');
        $deleteLinks = $this->db->prepare('DELETE FROM article_category WHERE article_id = :id');
        $link = $this->db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (:article, :category) ON CONFLICT DO NOTHING');
        foreach ($articles as $index => [$slug, $title, $views, $keys]) {
            $articleStatement->execute(['image' => '/assets/images/article-' . (($index % 3) + 1) . '.svg', 'title' => $title, 'slug' => $slug, 'description' => 'Короткое описание статьи «' . $title . '».', 'content' => "Материал посвящён теме «{$title}».\n\nДемонстрационный текст статьи для проверки MVC-блога.", 'views' => $views, 'published' => date('Y-m-d H:i:s', strtotime('-' . $index . ' days'))]);
            $findArticle->execute(['slug' => $slug]);
            $id = (int) $findArticle->fetchColumn();
            $deleteLinks->execute(['id' => $id]);
            foreach ($keys as $key) {
                $link->execute(['article' => $id, 'category' => $categories[$key]]);
            }
        }
    }
}
