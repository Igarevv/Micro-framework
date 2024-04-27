FROM php:8.3-fpm

WORKDIR "/var/www"

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo pdo_pgsql

COPY . .

