<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\{ArticleDto, CategoryDto};
use Doctrine\DBAL\Connection;

final class PostgresCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private readonly Connection $connection)
    {
    }
    public function withLatestArticles(int $limit = 3): array
    {
        $categories = $this->connection->fetchAllAssociative('SELECT c.* FROM categories c WHERE EXISTS (SELECT 1 FROM article_category ac JOIN articles a ON a.id = ac.article_id WHERE ac.category_id = c.id AND a.published_at <= CURRENT_TIMESTAMP) ORDER BY c.name');
        return array_map(function (array $row) use ($limit): CategoryDto {
            $category = $this->mapCategory($row);
            $articles = $this->connection->fetchAllAssociative('SELECT a.* FROM articles a JOIN article_category ac ON ac.article_id = a.id WHERE ac.category_id = ? AND a.published_at <= CURRENT_TIMESTAMP ORDER BY a.published_at DESC LIMIT ' . (int) $limit, [$category->id]);
            return new CategoryDto($category->id, $category->name, $category->slug, $category->description, array_map([$this, 'mapArticle'], $articles));
        }, $categories);
    }
    public function findBySlug(string $slug): ?CategoryDto
    {
        $row = $this->connection->fetchAssociative('SELECT * FROM categories WHERE slug = ? LIMIT 1', [$slug]);
        return $row ? $this->mapCategory($row) : null;
    }
    public function articles(CategoryDto $category, int $page, int $perPage, string $sort, string $direction): array
    {
        $order = $sort === 'views_count' ? 'a.views_count' : 'a.published_at';
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $total = (int) $this->connection->fetchOne('SELECT COUNT(*) FROM articles a JOIN article_category ac ON ac.article_id = a.id WHERE ac.category_id = ? AND a.published_at <= CURRENT_TIMESTAMP', [$category->id]);
        $items = $this->connection->fetchAllAssociative("SELECT a.* FROM articles a JOIN article_category ac ON ac.article_id = a.id WHERE ac.category_id = ? AND a.published_at <= CURRENT_TIMESTAMP ORDER BY {$order} {$direction}, a.id DESC LIMIT ? OFFSET ?", [$category->id, $perPage, ($page - 1) * $perPage], [\PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT]);
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
