SHELL := /bin/sh

COMPOSE ?= docker compose

.PHONY: setup env build up dev down restart migrate seed assets logs shell composer

setup: env build up migrate seed assets

env:
	@if [ ! -f .env ]; then cp .env.example .env; echo "Created .env from .env.example"; fi

build: env
	$(COMPOSE) build app

up: env
	$(COMPOSE) up -d app nginx db

dev: up
	$(COMPOSE) up -d vite

down:
	$(COMPOSE) down

restart: down up

migrate:
	$(COMPOSE) exec app php bin/migrations

seed:
	$(COMPOSE) exec app php database/seed.php

assets:
	$(COMPOSE) run --rm vite sh -c 'npm install && npm run build'

logs:
	$(COMPOSE) logs -f

shell:
	$(COMPOSE) exec app sh

composer:
	$(COMPOSE) run --rm app composer $(ARGS)
