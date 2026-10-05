# Simple PHP MVC Blog

Plain PHP 8.1+ MVC blog using Smarty, Symfony Components, Doctrine DBAL, MySQL and Vite.

## Local Docker setup

```bash
cp .env.example .env
docker compose build app
docker compose run --rm app composer install
docker compose up -d
docker compose exec app php database/seed.php
docker compose run --rm vite npm run build
```

Open http://localhost:8080.

The application uses Symfony HttpFoundation, Routing, DependencyInjection, Validator and Cache as independent components. It does not use Symfony Framework.

## Frontend

```bash
npm install
npm run dev
npm run build
```

The production CSS manifest is read by `App\View\AssetManager` and injected into Smarty layouts.
