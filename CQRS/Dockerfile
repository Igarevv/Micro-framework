FROM php:8.3-fpm

COPY . .
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    curl \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pgsql pdo_pgsql

CMD touch /usr/local/php-logs/php-fpm.log && \
    chmod 666 /usr/local/php-logs/php-fpm.log && \
    php-fpm