<?php

declare(strict_types=1);

namespace Database\Seeders;

use Doctrine\DBAL\Connection;

final class ArticleSeeder
{
    public function __construct(private readonly Connection $db)
    {
    }
    public function run(array $categories): void
    {
        $articles = [
            ['first-steps-php', 'Первые шаги в PHP', 'php', 120, ['php']], ['-pdo-and-db', 'Надёжная работа с PDO и DBAL', 'databases', 95, ['php', 'databases']], ['sql-indexes', 'Зачем нужны SQL-индексы', 'databases', 240, ['databases']], ['docker-for-php', 'Docker для PHP-проекта', 'devops', 180, ['devops', 'php']], ['clean-config', 'Конфигурация приложения', 'php', 80, ['php']], ['mysql-transactions', 'Транзакции в MySQL', 'databases', 155, ['databases']], ['nginx-php-fpm', 'Nginx и PHP-FPM', 'devops', 210, ['devops']], ['composer-autoload', 'Composer Autoload', 'php', 70, ['php']], ['database-migrations', 'Миграции базы данных', 'databases', 130, ['databases', 'devops']], ['scss-vite', 'SCSS и Vite', 'devops', 190, ['devops']], ['http-caching', 'Кэширование HTTP', 'php', 110, ['php', 'devops']], ['secure-input', 'Безопасная обработка входных данных', 'php', 275, ['php', 'databases']]
        ];
        foreach ($articles as $index => [$slug, $title, $categoryKey, $views, $keys]) {
            $this->db->executeStatement('INSERT INTO articles (image, title, slug, description, content, views_count, published_at) VALUES (?, ?, ?, ?, ?, ?, ?) ON CONFLICT (slug) DO UPDATE SET title = EXCLUDED.title, description = EXCLUDED.description, content = EXCLUDED.content, views_count = EXCLUDED.views_count, published_at = EXCLUDED.published_at', ['/assets/images/article-' . (($index % 3) + 1) . '.svg', $title, $slug, 'Короткое описание статьи «' . $title . '».', "Материал посвящён теме «{$title}».\n\nЗдесь находится демонстрационный текст статьи для проверки MVC-блога, шаблонов Smarty и работы с базой данных.", $views, date('Y-m-d H:i:s', strtotime('-' . $index . ' days'))]);
            $articleId = (int) $this->db->fetchOne('SELECT id FROM articles WHERE slug = ?', [$slug]);
            $this->db->executeStatement('DELETE FROM article_category WHERE article_id = ?', [$articleId]);
            foreach ($keys as $key) {
                $this->db->insert('article_category', ['article_id' => $articleId, 'category_id' => $categories[$key]]);
            }
        }
    }
}
