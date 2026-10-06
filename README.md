# Simple PHP MVC Blog

Plain PHP 8.1+ MVC blog using PDO, PostgreSQL and Vite.

## Local Docker setup

The complete first-time setup is available through the Makefile:

```bash
make setup
```

This creates `.env` from `.env.example`, builds the PHP image, runs `composer install`, starts the application services, applies migrations, seeds the database and builds frontend assets.

The individual setup steps are:

```bash
cp .env.example .env
# Set DB_USERNAME and a strong random DB_PASSWORD in .env before starting.
make build
make up
make migrate
make seed
make assets
```

Open http://localhost:8080.

The PHP image runs `composer install` during the `app` image build. Composer dependencies are kept in a named Docker volume so the project bind mount does not hide them.

PostgreSQL is available only inside the Compose network. The Vite development server is bound to localhost.

PostgreSQL credentials are applied when the data volume is initialized. Rotating `DB_USERNAME` or `DB_PASSWORD` for an existing volume requires updating the database role first, or deliberately recreating the disposable local volume.

Set `APP_PORT` in `.env` when port 8080 is already occupied.

Useful commands: `make down`, `make restart`, `make dev`, `make logs`, `make shell` and `make composer ARGS="validate"`.

The application uses native PHP code for HTTP, routing, dependency injection, validation, views and database access. PostgreSQL is accessed through the native `pdo_pgsql` driver. Composer is used for development tooling such as PHP CS Fixer.

PSR-7 and PSR-15 contracts and their runtime implementations are maintained locally under `src/Framework/Psr` and `src/Framework/Http`; the application has no external Composer packages.

## Application providers and routes

The kernel loads focused service providers from `src/Providers`: HTTP, views, repositories, blog services, controllers and routes are registered separately. Add HTTP routes in `routes/web.php` through the injected `Router` and resolve dependencies through the injected `Container`.

## Database migrations

```bash
php bin/migrations
php database/seed.php
```

SQL migrations are kept in `database/migrations` and are applied by the small native-PHP runner.

## Code style

Code follows PSR-12 formatting. Run `composer format` to apply formatting or `composer format:check` to verify it.

## Frontend

```bash
npm install
npm run dev
npm run build
```

The production CSS manifest is read by `App\View\AssetManager` and injected into native PHP layouts.
