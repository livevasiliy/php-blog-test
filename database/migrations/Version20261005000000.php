<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261005000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create blog categories, articles and many-to-many relation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE categories (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, slug VARCHAR(180) NOT NULL, description LONGTEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_CATEGORIES_SLUG (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id INT UNSIGNED AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, title VARCHAR(200) NOT NULL, slug VARCHAR(220) NOT NULL, description LONGTEXT NOT NULL, content LONGTEXT NOT NULL, views_count INT UNSIGNED DEFAULT 0 NOT NULL, published_at DATETIME NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_ARTICLES_SLUG (slug), INDEX ARTICLES_PUBLISHED_AT_IDX (published_at), INDEX ARTICLES_VIEWS_COUNT_IDX (views_count), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_category (article_id INT UNSIGNED NOT NULL, category_id INT UNSIGNED NOT NULL, INDEX IDX_ARTICLE_CATEGORY_ARTICLE (article_id), INDEX IDX_ARTICLE_CATEGORY_CATEGORY (category_id), PRIMARY KEY(article_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_category ADD CONSTRAINT FK_ARTICLE_CATEGORY_ARTICLE FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_category ADD CONSTRAINT FK_ARTICLE_CATEGORY_CATEGORY FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE article_category');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE categories');
    }
}
