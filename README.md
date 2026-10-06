# Простой PHP MVC-блог

Простой MVC-блог на чистом PHP 8.1+ с использованием PDO, PostgreSQL и Vite.

## Локальный запуск через Docker

Для полного разворачивания проекта с нуля выполните:

```bash
make setup
```

Команда создаст `.env` из `.env.example`, соберёт PHP-образ, выполнит `composer install`, запустит сервисы приложения, применит миграции, добавит тестовые данные и соберёт frontend-ассеты.

Если нужно выполнить шаги отдельно:

```bash
cp .env.example .env
# При необходимости измените DB_USERNAME и задайте надёжный DB_PASSWORD.
make build
make up
make migrate
make seed
make assets
```

После запуска приложение доступно по адресу http://localhost:8080.

Во время сборки PHP-образа выполняется `composer install`. Composer-зависимости хранятся в отдельном Docker volume, поэтому монтирование проекта не скрывает папку `vendor`.

PostgreSQL доступен только внутри сети Docker Compose. Vite-сервер разработки привязан к localhost.

Учётные данные PostgreSQL применяются при первоначальной инициализации volume. Для изменения `DB_USERNAME` или `DB_PASSWORD` у существующего volume потребуется сначала изменить роль в базе или пересоздать локальный volume.

Если порт 8080 уже занят, задайте другой `APP_PORT` в `.env`.

Полезные команды:

```bash
make down
make restart
make dev
make logs
make shell
make composer ARGS="validate"
```

## Архитектура приложения

Ядро загружает отдельные сервис-провайдеры. Framework-провайдеры отвечают за HTTP, представления и подключение PDO, а application-провайдеры — за репозитории, блоговые сервисы, контроллеры и маршруты.

HTTP-маршруты добавляются в `routes/web.php` через внедрённый `Router`. Зависимости можно получать через внедрённый `Container`.

Приложение использует собственные реализации HTTP, маршрутизации, dependency injection, валидации, представлений и доступа к базе данных. PostgreSQL подключается через нативный драйвер `pdo_pgsql`. Composer используется для инструментов разработки, например PHP CS Fixer.

Контракты PSR-7 и PSR-15, а также их runtime-реализации находятся в `src/Framework/Psr` и `src/Framework/Http`.

## Миграции и тестовые данные

Через Makefile:

```bash
make migrate
make seed
```

Или напрямую в локальном окружении:

```bash
php bin/migrations
php database/seed.php
```

SQL-миграции находятся в `database/migrations` и применяются небольшим нативным PHP-скриптом.

## Стиль кода

Код форматируется по PSR-12:

```bash
composer format
composer format:check
```

## Frontend

```bash
npm install
npm run dev
npm run build
```

Production-манифест CSS читается через `App\Framework\View\AssetManager` и подключается в нативные PHP-шаблоны.
