FROM php:8.2-fpm-alpine

# Install MySQL extension
RUN docker-php-ext-install pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install ZIP extensions
RUN apk add --no-cache zip libzip-dev \
    && docker-php-ext-install zip

WORKDIR /var/www/html