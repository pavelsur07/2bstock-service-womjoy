FROM php:8.2-fpm-alpine

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

# PHP extensions
RUN docker-php-ext-install intl pdo pdo_pgsql opcache xml zip

# Установка composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копируем проект
WORKDIR /var/www/html
COPY ./site /var/www/html

# Устанавливаем зависимости PHP без dev
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Удаляем временные файлы composer
RUN rm -rf /root/.composer/cache

# Настройки прав
RUN chown -R www-data:www-data /var/www/html

CMD ["php-fpm"]
