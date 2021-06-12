#!/bin/bash
echo "const config = {'app_url': '//$VIRTUAL_HOST/','api_url': '//$API_URL','login_url': '//$VIRTUAL_HOST/login/'}" > /var/www/html/config.js
exec docker-php-entrypoint apache2-foreground