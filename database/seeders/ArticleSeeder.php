<?php

declare(strict_types=1);

namespace Database\Seeders;

use PDO;

final class ArticleSeeder
{
    private const ADDITIONAL_ARTICLES_PER_CATEGORY = 10;
    private const FIRST_DEMO_NUMBER = 1;
    private const BASE_VIEWS = 40;
    private const VIEWS_STEP = 13;
    private const IMAGE_COUNT = 3;
    private const FIRST_IMAGE_NUMBER = 1;

    public function __construct(private readonly PDO $db)
    {
    }

    public function run(array $categories): void
    {
        $articles = [
            ['first-steps-php', 'Первые шаги в PHP', 120, ['php']],
            ['pdo-and-db', 'Надёжная работа с PDO', 95, ['php', 'databases']],
            ['sql-indexes', 'Зачем нужны SQL-индексы', 240, ['databases']],
            ['docker-for-php', 'Docker для PHP-проекта', 180, ['devops', 'php']],
            ['clean-config', 'Конфигурация приложения', 80, ['php']],
        ];
        $additionalTopics = [
            'php' => 'Практика разработки на PHP',
            'databases' => 'Работа с базами данных',
            'devops' => 'Инструменты DevOps',
        ];
        foreach ($additionalTopics as $category => $topic) {
            for ($number = self::FIRST_DEMO_NUMBER; $number <= self::ADDITIONAL_ARTICLES_PER_CATEGORY; $number++) {
                $articles[] = [
                    sprintf('demo-%s-%02d', $category, $number),
                    sprintf('%s: пример %d', $topic, $number),
                    self::BASE_VIEWS + ($number * self::VIEWS_STEP),
                    [$category],
                ];
            }
        }
        $articleStatement = $this->db->prepare('INSERT INTO articles (image, title, slug, description, content, views_count, published_at) VALUES (:image, :title, :slug, :description, :content, :views, :published) ON CONFLICT (slug) DO UPDATE SET title = EXCLUDED.title, description = EXCLUDED.description, content = EXCLUDED.content, views_count = EXCLUDED.views_count, published_at = EXCLUDED.published_at');
        $findArticle = $this->db->prepare('SELECT id FROM articles WHERE slug = :slug');
        $deleteLinks = $this->db->prepare('DELETE FROM article_category WHERE article_id = :id');
        $link = $this->db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (:article, :category) ON CONFLICT DO NOTHING');
        foreach ($articles as $index => [$slug, $title, $views, $keys]) {
            $imageNumber = ($index % self::IMAGE_COUNT) + self::FIRST_IMAGE_NUMBER;
            $articleStatement->execute(['image' => '/assets/images/article-' . $imageNumber . '.svg', 'title' => $title, 'slug' => $slug, 'description' => 'Короткое описание статьи «' . $title . '».', 'content' => "Материал посвящён теме «{$title}».\n\nДемонстрационный текст статьи для проверки MVC-блога.", 'views' => $views, 'published' => date('Y-m-d H:i:s', strtotime('-' . $index . ' days'))]);
            $findArticle->execute(['slug' => $slug]);
            $id = (int) $findArticle->fetchColumn();
            $deleteLinks->execute(['id' => $id]);
            foreach ($keys as $key) {
                $link->execute(['article' => $id, 'category' => $categories[$key]]);
            }
        }
    }
}
