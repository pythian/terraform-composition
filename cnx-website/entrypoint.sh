#!/bin/sh
set -e

# Start SSH
service ssh start

# Start PHP
/etc/init.d/php8.3-fpm start

# Start Apache in the foreground
apache2ctl -D FOREGROUND
