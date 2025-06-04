#!/bin/sh
set -e

# Start SSH
service ssh start

# Create the env variables
echo ''; >> /etc/php/8.3/fpm/pool.d/www.conf
echo "env[CANONICAL_URL] = '$CANONICAL_URL'" >> /etc/php/8.3/fpm/pool.d/www.conf
echo "env[CANONICAL_URL_ALTERNATIVE] = '$CANONICAL_URL_ALTERNATIVE'" >> /etc/php/8.3/fpm/pool.d/www.conf
echo "env[MYSQL_ADDR] = '$MYSQL_ADDR'" >> /etc/php/8.3/fpm/pool.d/www.conf
echo "env[MYSQL_PASSWD] = '$MYSQL_PASSWD'" >> /etc/php/8.3/fpm/pool.d/www.conf
echo "env[MYSQL_USER] = '$MYSQL_USER'" >> /etc/php/8.3/fpm/pool.d/www.conf

# Start PHP
/etc/init.d/php8.3-fpm start

# Start Apache in the foreground
apache2ctl -D FOREGROUND
