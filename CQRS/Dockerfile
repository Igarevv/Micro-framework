FROM php:8.3-fpm
COPY . .
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git

RUN docker-php-ext-install pgsql pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


