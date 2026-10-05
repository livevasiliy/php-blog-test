FROM php:8.4-fpm-alpine
RUN apk add --no-cache --virtual .pgsql-build-deps postgresql-dev $PHPIZE_DEPS \
    && docker-php-ext-install pdo_pgsql \
    && apk del .pgsql-build-deps
WORKDIR /var/www/html
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
