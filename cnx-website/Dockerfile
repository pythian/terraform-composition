FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y git curl libmcrypt-dev default-mysql-client \
    && docker-php-ext-install mysqli


RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod ssl

COPY ./public-html/ /var/www/html/

RUN service apache2 restart
