FROM php:8.2-cli-alpine

# Установка зависимостей
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    icu-dev \
    libxml2-dev \
    oniguruma-dev \
    postgresql-dev \
    libzip-dev \
    zlib-dev

RUN docker-php-ext-install intl pdo pdo_pgsql opcache xml zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY ./site /var/www/html

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

RUN rm -rf /root/.composer/cache

RUN chown -R www-data:www-data /var/www/html

ENTRYPOINT ["php"]
