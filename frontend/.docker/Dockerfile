# PHP 8.4 — patched against CVE-2026-109618/109619 (PHP2Shell). Rebuild pulls >= 8.4.27.
FROM php:8.5-apache
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
RUN a2enmod ssl
RUN a2enmod headers

COPY ./src /var/www/html/

COPY ./.docker/entrypoint.sh /usr/local/bin/
RUN ["chmod", "+x", "/usr/local/bin/entrypoint.sh"]
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]