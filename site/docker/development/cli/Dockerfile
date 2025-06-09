FROM php:8.1-cli-alpine
RUN apk add --no-cache postgresql-dev curl zip unzip \
    && docker-php-ext-install pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

WORKDIR /app
USER app
