FROM php:8.4-fpm-alpine
RUN docker-php-ext-install pdo_mysql
WORKDIR /var/www/html
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
