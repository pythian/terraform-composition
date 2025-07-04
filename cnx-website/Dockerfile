FROM ubuntu:noble-20250415.1

RUN apt-get update -y \
    && apt-get install -y git gzip curl libmcrypt-dev default-mysql-client samba smbclient libsmbclient-dev libfreetype6-dev libjpeg62-dev libpng-dev \
    && apt-get install -y --no-install-recommends dialog \
    && apt-get install -y --no-install-recommends openssh-server \
    && apt-get install -y --no-install-recommends apache2 libapache2-mod-php libapache2-mod-fcgid openssl \
    php php-cli php-common php-curl php-fpm php-gd php-mbstring php-mysql php8.3-opcache php-smbclient \
    php-symfony-polyfill-ctype php-symfony-dom-crawler php-symfony-dotenv php-symfony-polyfill-iconv php-xml php-zip

RUN a2dismod php8.3 mpm_prefork
RUN a2disconf serve-cgi-bin
RUN a2enconf php8.3-fpm && a2enmod mpm_event
RUN a2enmod actions fcgid alias proxy_fcgi
RUN a2enmod rewrite headers expires setenvif
RUN a2enmod cache cache_disk deflate

# delete the installed "index.html"
RUN rm -rvf /var/www/html/*

# copy the site contents
COPY ./public-html/ /var/www/html/
RUN chmod -R 775 /var/www/html && chown -R www-data:www-data /var/www/html

# logs should go to stdout / stderr
RUN ln -sfT /dev/stderr /var/log/apache2/error.log; \
    ln -sfT /dev/stdout /var/log/apache2/access.log; \
    ln -sfT /dev/stdout /var/log/apache2/other_vhosts_access.log; \
    chown -R --no-dereference www-data:www-data /var/log/apache2
RUN ln -sfT /dev/stderr /var/log/php8.3-fpm.log;

# place the virtual server config
COPY ./001-concrete.conf /etc/apache2/sites-available/001-concrete.conf

# place the event module config
COPY ./mpm_event.conf /etc/apache2/mods-available/mpm_event.conf

# place the fcgi config
COPY ./php8.3-fpm.conf /etc/apache2/conf-available/php8.3-fpm.conf

RUN a2dissite 000-default && a2ensite 001-concrete
COPY entrypoint.sh ./

# add the following lines at the end of /etc/apache2/apache2.conf
RUN { \
        echo ''; \
        echo '<Directory /var/www/html>'; \
        echo '    Options Indexes FollowSymLinks'; \
        echo '    AllowOverride All'; \
        echo '    Require all granted'; \
        echo '</Directory>'; \
        echo ''; \
        echo '#Set caching directives'; \
        echo 'CacheRoot "/var/cache/apache2/mod_cache_disk"'; \
        echo 'CacheEnable disk /'; \
        echo 'CacheDirLevels 2'; \
        echo 'CacheDirLength 1'; \
        echo 'CacheIgnoreHeaders Set-Cookie'; \
        echo ''; \
        echo 'ServerName connexusenergy.com'; \
    } >> /etc/apache2/apache2.conf

RUN sed -ri -e 's/Timeout 300/Timeout 120/g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# php-fpm compression
RUN sed -ri -e 's/zlib.output_compression = Off/zlib.output_compression = On/' /etc/php/8.3/fpm/php.ini
RUN sed -ri -e 's/;zlib.output_compression_level = -1/zlib.output_compression_level = -1/' /etc/php/8.3/fpm/php.ini

# handle sessions
RUN sed -ri -e 's:;session.save_path = "/var/lib/php/sessions":session.save_path = "/var/lib/php-fpm/sessions":' /etc/php/8.3/fpm/php.ini
RUN { \
    mkdir -p /var/lib/php-fpm/sessions; \
    chmod 755 /var/lib/php-fpm/sessions; \
    chown www-data:www-data /var/lib/php-fpm/sessions; \
    }

# short open tag
RUN sed -ri -e 's/short_open_tag = Off/short_open_tag = On/' /etc/php/8.3/fpm/php.ini

# configure php-fpm to use the environment
RUN sed -ri -e 's/variables_order = "GPCS"/variables_order = "EGPCS"/' /etc/php/8.3/fpm/php.ini
RUN { \
        echo ''; \
        echo 'env[CANONICAL_URL] = $CANONICAL_URL'; \
        echo 'env[CANONICAL_URL_ALTERNATIVE] = $CANONICAL_URL_ALTERNATIVE'; \
        echo 'env[MYSQL_ADDR] = $MYSQL_ADDR'; \
        echo 'env[MYSQL_PASSWD] = $MYSQL_PASSWD'; \
        echo 'env[MYSQL_USER] = $MYSQL_USER'; \
    } >> /etc/php/8.3/fpm/pool.d/www.conf

# tune the fpm pool
RUN sed -ri -e 's/pm.max_children.*/pm.max_children = 64/' /etc/php/8.3/fpm/pool.d/www.conf
RUN sed -ri -e 's/pm.start_servers.*/pm.start_servers = 12/' /etc/php/8.3/fpm/pool.d/www.conf
RUN sed -ri -e 's/pm.min_spare_servers.*/pm.min_spare_servers = 4/' /etc/php/8.3/fpm/pool.d/www.conf
RUN sed -ri -e 's/pm.max_spare_servers.*/pm.max_spare_servers = 12/' /etc/php/8.3/fpm/pool.d/www.conf
RUN sed -ri -e 's/;pm.max_spawn_rate.*/pm.max_spawn_rate = 16/' /etc/php/8.3/fpm/pool.d/www.conf

# start and enable SSH inside the webApp only
RUN echo "root:Docker!" | chpasswd \
    && chmod u+x ./entrypoint.sh
COPY sshd_config /etc/ssh/

ENV PORT=80
EXPOSE 80 2222

ENTRYPOINT [ "./entrypoint.sh" ]
