FROM php:8.0-apache
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./.docker/php.ini /usr/local/etc/php
RUN a2enmod rewrite

RUN a2enmod ssl
COPY ./src /var/www/html/

COPY ./.docker/entrypoint.sh /usr/local/bin/
RUN ["chmod", "+x", "/usr/local/bin/entrypoint.sh"]
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]