<?php

declare(strict_types=1);

namespace Database\Seeders;

use PDO;

final class CategorySeeder
{
    public function __construct(private readonly PDO $db)
    {
    }

    public function run(): array
    {
        $rows = [['PHP', 'php', 'Практика разработки на PHP.'], ['Базы данных', 'databases', 'SQL, проектирование и оптимизация баз данных.'], ['DevOps', 'devops', 'Контейнеры, окружение и процессы доставки.']];
        $ids = [];
        $statement = $this->db->prepare('INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description) ON CONFLICT (slug) DO UPDATE SET name = EXCLUDED.name, description = EXCLUDED.description');
        foreach ($rows as [$name, $slug, $description]) {
            $statement->execute(['name' => $name, 'slug' => $slug, 'description' => $description]);
            $find = $this->db->prepare('SELECT id FROM categories WHERE slug = :slug');
            $find->execute(['slug' => $slug]);
            $ids[$slug] = (int) $find->fetchColumn();
        }
        return $ids;
    }
}
