# Simple PHP MVC Blog

Plain PHP 8.1+ MVC blog using PDO, PostgreSQL and Vite.

## Local Docker setup

```bash
cp .env.example .env
docker compose build app
docker compose up -d
docker compose exec app php bin/migrations
docker compose exec app php database/seed.php
docker compose run --rm vite npm run build
```

Open http://localhost:8080.

Set `APP_PORT` in `.env` when port 8080 is already occupied.

The application uses only native PHP code for HTTP, routing, dependency injection, validation, views and database access. PostgreSQL is accessed through the native `pdo_pgsql` driver.

## Database migrations

```bash
php bin/migrations
php database/seed.php
```

SQL migrations are kept in `database/migrations` and are applied by the small native-PHP runner.

## Code style

Code follows PSR-12 formatting manually; runtime does not require a code-style package.

## Frontend

```bash
npm install
npm run dev
npm run build
```

The production CSS manifest is read by `App\View\AssetManager` and injected into native PHP layouts.
