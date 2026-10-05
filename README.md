# Simple PHP MVC Blog

Plain PHP 8.1+ MVC blog using Smarty, Symfony Components, Doctrine DBAL, MySQL and Vite.

## Local Docker setup

```bash
cp .env.example .env
docker compose build app
docker compose run --rm app composer install
docker compose up -d
docker compose exec app composer migrate
docker compose exec app php database/seed.php
docker compose run --rm vite npm run build
```

Open http://localhost:8080.

The application uses Symfony HttpFoundation, Routing, DependencyInjection, Validator and Cache as independent components. It does not use Symfony Framework.

## Database migrations

```bash
composer migrate
composer migration-status
composer seed
```

Schema changes are versioned with Doctrine Migrations. Run migrations before seeding.

## Code style

```bash
composer cs:check
composer cs:fix
```

PHP-CS-Fixer is configured with PSR-12 rules in `.php-cs-fixer.dist.php`.

## Frontend

```bash
npm install
npm run dev
npm run build
```

The production CSS manifest is read by `App\View\AssetManager` and injected into Smarty layouts.
