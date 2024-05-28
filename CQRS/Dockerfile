FROM php:8.3-fpm
COPY . .
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pgsql pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


