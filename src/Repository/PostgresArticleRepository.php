<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\ArticleDto;
use Doctrine\DBAL\Connection;

final class PostgresArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(private readonly Connection $connection)
    {
    }
    public function findBySlug(string $slug): ?ArticleDto
    {
        $row = $this->connection->fetchAssociative('SELECT a.* FROM articles a WHERE a.slug = ? AND a.published_at <= CURRENT_TIMESTAMP LIMIT 1', [$slug]);
        return $row ? $this->map($row) : null;
    }
    public function similar(ArticleDto $article, int $limit = 3): array
    {
        $rows = $this->connection->fetchAllAssociative('SELECT a.*, COUNT(ac2.category_id) AS shared_categories FROM articles a JOIN article_category ac2 ON ac2.article_id = a.id JOIN article_category ac1 ON ac1.category_id = ac2.category_id WHERE ac1.article_id = ? AND a.id <> ? AND a.published_at <= CURRENT_TIMESTAMP GROUP BY a.id ORDER BY shared_categories DESC, a.published_at DESC LIMIT ' . (int) $limit, [$article->id, $article->id]);
        return array_map(fn (array $row): ArticleDto => $this->map($row), $rows);
    }
    public function incrementViews(int $id): void
    {
        $this->connection->executeStatement('UPDATE articles SET views_count = views_count + 1 WHERE id = ?', [$id]);
    }
    private function map(array $row): ArticleDto
    {
        return new ArticleDto((int) $row['id'], $row['image'], $row['title'], $row['slug'], $row['description'], $row['content'], (int) $row['views_count'], $row['published_at']);
    }
}
