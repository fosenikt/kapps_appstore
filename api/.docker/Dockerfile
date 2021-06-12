FROM php:8.0-apache

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

#RUN docker-php-ext-configure gd
#RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ # Not working
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install -j$(nproc) iconv


RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer;


COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./.docker/php.ini /usr/local/etc/php


RUN a2enmod rewrite
RUN a2enmod ssl

#COPY ./src /tmp
#RUN rm -rf /var/www/html && mv /tmp /var/www/html &&\
#    find /var/www/html/ -type d -exec chmod 755 {} \; &&\
#    find /var/www/html/ -type f -exec chmod 644 {} \; &&\
#    chmod -R 777 /var/www/html/db

#USER root
COPY ./src /var/www/html/
#COPY ./src/database.sql /var/www/html/db/
#RUN chown -R www-data /var/www/html/db
#RUN chown -R www-data /var/www/html/data

#change ownership of our applications
RUN chown -R www-data:www-data /var/www/html


#COPY ./.docker/entrypoint.sh /usr/bin/entrypoint.sh
#RUN chmod 777 /usr/bin/entrypoint.sh
#ENTRYPOINT ["/usr/bin/entrypoint.sh"]
#CMD ["apache2-foreground"]

