#!/bin/sh
echo "Hello"
source /etc/apache2/envvars
exec apache2 -D FOREGROUND