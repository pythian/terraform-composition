FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y git gzip curl libmcrypt-dev default-mysql-client samba smbclient libsmbclient-dev \
    && apt-get install -y --no-install-recommends dialog \
    && apt-get install -y --no-install-recommends openssh-server \
    && docker-php-ext-install mysqli pdo pdo_mysql opcache \
    && pecl install smbclient \
    && docker-php-ext-enable pdo_mysql opcache smbclient

RUN a2enmod rewrite
RUN a2enmod headers

COPY ./public-html/ /var/www/html/
RUN chmod -R 775 /var/www/html && chown -R www-data:www-data /var/www/html
RUN mkdir -p /var/php/sessions/ea-php82 && chown -R www-data:www-data /var/php/sessions/ea-php82 && chmod -R 775 /var/php/sessions/ea-php82


COPY ./php.ini $PHP_INI_DIR/php.ini
COPY entrypoint.sh ./

# Start and enable SSH inside the webApp only
RUN echo "root:Docker!" | chpasswd \
    && chmod u+x ./entrypoint.sh
COPY sshd_config /etc/ssh/

ENV PORT=80
EXPOSE 80 2222

ENTRYPOINT [ "./entrypoint.sh" ]
