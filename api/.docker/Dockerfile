#Get Composer
FROM composer:2.0 as vendor

FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
		libldap2-dev \
		libz-dev \
		libpng-dev \
		zlib1g-dev \
		libmemcached-dev \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
		libjpeg-dev \
		libzip-dev \
		libonig-dev \
		# Build Deps
		build-essential curl make && \
		rm -rf /var/lib/apt/lists/*

# Install Memcached for php 7
RUN set -ex \
    && apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y libmemcached-dev \
    && rm -rf /var/lib/apt/lists/* \
    && MEMCACHED="`mktemp -d`" \
    && curl -skL https://github.com/php-memcached-dev/php-memcached/archive/master.tar.gz | tar zxf - --strip-components 1 -C $MEMCACHED \
    && docker-php-ext-configure $MEMCACHED \
    && docker-php-ext-install $MEMCACHED \
    && rm -rf $MEMCACHED


RUN apt-get update && apt-get install -y unzip

# Add the stretch repo so the old freetype6 versions are available
RUN echo "deb http://deb.debian.org/debian stretch main" >> /etc/apt/sources.list


RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install -j$(nproc) iconv

#RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer;

COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
RUN a2enmod ssl

#USER root
COPY ./src /var/www/html/

#change ownership of our applications
RUN chown -R www-data:www-data /var/www/html



COPY --from=vendor /usr/bin/composer /usr/bin/composer
COPY ./src/composer.json composer.json
#COPY composer.lock composer.lock
RUN composer update --no-dev

