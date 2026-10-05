<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\{ArticleDto, CategoryDto};
final class PostgresCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private readonly \PDO $connection)
    {
    }
    public function withLatestArticles(int $limit = 3): array
    {
        $categories = $this->connection->query('SELECT c.* FROM categories c WHERE EXISTS (SELECT 1 FROM article_category ac JOIN articles a ON a.id = ac.article_id WHERE ac.category_id = c.id AND a.published_at <= CURRENT_TIMESTAMP) ORDER BY c.name')->fetchAll();
        return array_map(function (array $row) use ($limit): CategoryDto {
            $category = $this->mapCategory($row);
            $statement = $this->connection->prepare('SELECT a.* FROM articles a JOIN article_category ac ON ac.article_id = a.id WHERE ac.category_id = :id AND a.published_at <= CURRENT_TIMESTAMP ORDER BY a.published_at DESC LIMIT ' . (int) $limit);
            $statement->execute(['id' => $category->id]);
            $articles = $statement->fetchAll();
            return new CategoryDto($category->id, $category->name, $category->slug, $category->description, array_map([$this, 'mapArticle'], $articles));
        }, $categories);
    }
    public function findBySlug(string $slug): ?CategoryDto
    {
        $statement = $this->connection->prepare('SELECT * FROM categories WHERE slug = :slug LIMIT 1');
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();
        return $row ? $this->mapCategory($row) : null;
    }
    public function articles(CategoryDto $category, int $page, int $perPage, string $sort, string $direction): array
    {
        $order = $sort === 'views_count' ? 'a.views_count' : 'a.published_at';
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $statement = $this->connection->prepare('SELECT COUNT(*) FROM articles a JOIN article_category ac ON ac.article_id = a.id WHERE ac.category_id = :id AND a.published_at <= CURRENT_TIMESTAMP');
        $statement->execute(['id' => $category->id]);
        $total = (int) $statement->fetchColumn();
        $statement = $this->connection->prepare("SELECT a.* FROM articles a JOIN article_category ac ON ac.article_id = a.id WHERE ac.category_id = :id AND a.published_at <= CURRENT_TIMESTAMP ORDER BY {$order} {$direction}, a.id DESC LIMIT :limit OFFSET :offset");
        $statement->bindValue('id', $category->id, \PDO::PARAM_INT);
        $statement->bindValue('limit', $perPage, \PDO::PARAM_INT);
        $statement->bindValue('offset', ($page - 1) * $perPage, \PDO::PARAM_INT);
        $statement->execute();
        $items = $statement->fetchAll();
        return ['items' => array_map([$this, 'mapArticle'], $items), 'total' => $total];
    }
    private function mapCategory(array $row): CategoryDto
    {
        return new CategoryDto((int) $row['id'], $row['name'], $row['slug'], $row['description']);
    }
    private function mapArticle(array $row): ArticleDto
    {
        return new ArticleDto((int) $row['id'], $row['image'], $row['title'], $row['slug'], $row['description'], $row['content'], (int) $row['views_count'], $row['published_at']);
    }
}
