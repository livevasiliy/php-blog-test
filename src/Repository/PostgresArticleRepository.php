<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Article;

final class PostgresArticleRepository implements ArticleRepositoryInterface
{
    private const SINGLE_RESULT_LIMIT = 1;
    private const VIEW_INCREMENT = 1;

    public function __construct(private readonly \PDO $connection)
    {
    }

    public function findBySlug(string $slug): ?Article
    {
        $statement = $this->connection->prepare('SELECT a.* FROM articles a WHERE a.slug = :slug AND a.published_at <= CURRENT_TIMESTAMP LIMIT ' . self::SINGLE_RESULT_LIMIT);
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();
        return $row ? $this->map($row) : null;
    }

    public function similar(Article $article, int $limit = self::DEFAULT_SIMILAR_LIMIT): array
    {
        $statement = $this->connection->prepare('SELECT a.*, COUNT(ac2.category_id) AS shared_categories FROM articles a JOIN article_category ac2 ON ac2.article_id = a.id JOIN article_category ac1 ON ac1.category_id = ac2.category_id WHERE ac1.article_id = :article AND a.id <> :excluded AND a.published_at <= CURRENT_TIMESTAMP GROUP BY a.id ORDER BY shared_categories DESC, a.published_at DESC LIMIT ' . (int) $limit);
        $statement->execute(['article' => $article->id, 'excluded' => $article->id]);
        $rows = $statement->fetchAll();
        return array_map(fn (array $row): Article => $this->map($row), $rows);
    }

    public function incrementViews(int $id): void
    {
        $statement = $this->connection->prepare('UPDATE articles SET views_count = views_count + ' . self::VIEW_INCREMENT . ' WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    private function map(array $row): Article
    {
        return new Article((int) $row['id'], $row['image'], $row['title'], $row['slug'], $row['description'], $row['content'], (int) $row['views_count'], $row['published_at']);
    }
}
