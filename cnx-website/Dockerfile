FROM ubuntu:noble-20250415.1

RUN apt-get update -y \
    && apt-get install -y git gzip curl libmcrypt-dev default-mysql-client samba smbclient libsmbclient-dev libfreetype6-dev libjpeg62-dev libpng-dev \
    && apt-get install -y --no-install-recommends dialog \
    && apt-get install -y --no-install-recommends openssh-server \
    && apt-get install -y --no-install-recommends apache2 libapache2-mod-php libapache2-mod-fcgid php php-fpm php-cli  \
    && apt-get install -y --no-install-recommends php-mysql php-gd php8.3-opcache php-smbclient

RUN a2dismod php8.3 mpm_prefork
RUN a2enconf php8.3-fpm && a2enmod mpm_event
RUN a2enmod actions fcgid alias proxy_fcgi
RUN a2enmod rewrite headers expires
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

COPY ./001-concrete.conf /etc/apache2/sites-available/001-concrete.conf

RUN a2dissite 000-default && a2ensite 001-concrete
COPY entrypoint.sh ./

#add the following lines at the end of /etc/apache2/apache2.conf
RUN { \
        echo ''; \
        echo '#Set caching directives'; \
        echo 'CacheRoot "/var/cache/apache2/mod_cache_disk"'; \
        echo 'CacheEnable disk /'; \
        echo 'CacheDirLevels 2'; \
        echo 'CacheDirLength 1'; \
        echo 'CacheIgnoreHeaders Set-Cookie'; \
        echo ''; \
        echo '#Set Servername to prevent AH00558'; \
        echo 'ServerName 127.0.0.1'; \
    } >> "/etc/apache2/apache2.conf"

    RUN sed -ri -e 's/Timeout 300/Timeout 30/g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Start and enable SSH inside the webApp only
RUN echo "root:Docker!" | chpasswd \
    && chmod u+x ./entrypoint.sh
COPY sshd_config /etc/ssh/

ENV PORT=80
EXPOSE 80 2222

ENTRYPOINT [ "./entrypoint.sh" ]
