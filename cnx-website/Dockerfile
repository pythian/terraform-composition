FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y git gzip curl libmcrypt-dev default-mysql-client samba smbclient libsmbclient-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && apt-get install -y --no-install-recommends dialog \
    && apt-get install -y --no-install-recommends openssh-server \
    && docker-php-ext-install mysqli pdo pdo_mysql opcache gd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && pecl install smbclient \
    && docker-php-ext-enable pdo_mysql opcache smbclient gd

RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod expires
RUN a2enmod cache
RUN a2enmod cache_disk
RUN a2enmod deflate

COPY ./public-html/ /var/www/html/
RUN chmod -R 775 /var/www/html && chown -R www-data:www-data /var/www/html
RUN mkdir -p /var/php/sessions/ea-php82 && chown -R www-data:www-data /var/php/sessions/ea-php82 && chmod -R 775 /var/php/sessions/ea-php82


COPY ./php.ini $PHP_INI_DIR/php.ini
COPY entrypoint.sh ./

#add the following lines at the end of /etc/apache2/apache2.conf
RUN echo "CacheRoot \"/var/cache/apache2/mod_cache_disk\" " >> /etc/apache2/apache2.conf
RUN echo "CacheEnable disk /" >> /etc/apache2/apache2.conf
RUN echo "CacheDirLevels 2" >> /etc/apache2/apache2.conf
RUN echo "CacheDirLength 1" >> /etc/apache2/apache2.conf
RUN echo "CacheIgnoreHeaders Set-Cookie" >> /etc/apache2/apache2.conf
RUN sed -ri -e 's/Timeout 300/Timeout 30/g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf



# Start and enable SSH inside the webApp only
RUN echo "root:Docker!" | chpasswd \
    && chmod u+x ./entrypoint.sh
COPY sshd_config /etc/ssh/

ENV PORT=80
EXPOSE 80 2222

ENTRYPOINT [ "./entrypoint.sh" ]
