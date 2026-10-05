<?php

declare(strict_types=1);

namespace Database\Seeders;

use Doctrine\DBAL\Connection;

final class CategorySeeder
{
    public function __construct(private readonly Connection $db)
    {
    }
    public function run(): array
    {
        $rows = [['name' => 'PHP', 'slug' => 'php', 'description' => 'Практика разработки на PHP.'], ['name' => 'Базы данных', 'slug' => 'databases', 'description' => 'SQL, проектирование и оптимизация баз данных.'], ['name' => 'DevOps', 'slug' => 'devops', 'description' => 'Контейнеры, окружение и процессы доставки.']];
        $ids = [];
        foreach ($rows as $row) {
            $this->db->executeStatement('INSERT INTO categories (name, slug, description) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description)', array_values($row));
            $ids[$row['slug']] = (int) $this->db->fetchOne('SELECT id FROM categories WHERE slug = ?', [$row['slug']]);
        }
        return $ids;
    }
}
