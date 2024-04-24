FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo pdo_pgsql

COPY . /var/www

WORKDIR /var/www

