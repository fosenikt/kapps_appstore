# Get Composer
FROM composer:2.0 as vendor

FROM php:8.2-apache

# Install necessary extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev \
    unzip \
    && docker-php-ext-install -j$(nproc) intl \
    && pecl install apcu \
    && docker-php-ext-enable apcu

RUN docker-php-ext-install zip
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install exif
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install -j$(nproc) iconv

COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./.docker/php.ini /usr/local/etc/php/php.ini

RUN a2enmod rewrite
RUN a2enmod ssl

COPY ./src /var/www/html/

RUN chown -R www-data:www-data /var/www/html

COPY --from=vendor /usr/bin/composer /usr/bin/composer
COPY ./src/composer.json composer.json
RUN composer update --no-dev
