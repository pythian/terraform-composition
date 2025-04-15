FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y git curl libmcrypt-dev default-mysql-client \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

RUN a2enmod rewrite
RUN a2enmod headers

COPY ./public-html/ /var/www/html/
COPY ./php.ini $PHP_INI_DIR/php.ini

ENV PORT=80
EXPOSE 80


CMD ["apache2ctl", "-D", "FOREGROUND"]
