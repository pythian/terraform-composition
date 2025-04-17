FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y git gzip curl libmcrypt-dev default-mysql-client \
    && docker-php-ext-install mysqli pdo pdo_mysql opcache \
    && docker-php-ext-enable pdo_mysql opcache

RUN a2enmod rewrite
RUN a2enmod headers

COPY ./public-html/ /var/www/html/
RUN chmod -R 775 /var/www/html && chown -R www-data:www-data /var/www/html
RUN mkdir -p /var/php/sessions/ea-php82 && chown -R www-data:www-data /var/php/sessions/ea-php82 && chmod -R 775 /var/php/sessions/ea-php82


COPY ./php.ini $PHP_INI_DIR/php.ini

ENV PORT=80
EXPOSE 80


CMD ["apache2ctl", "-D", "FOREGROUND"]
