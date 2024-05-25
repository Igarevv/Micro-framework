FROM php:8.3-fpm

WORKDIR "/var/www"

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git

RUN docker-php-ext-configure postgres -with-postgres=/usr/local/postgres \
    && docker-php-ext-install postgres pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .
