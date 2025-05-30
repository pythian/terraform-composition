FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y git gzip curl libmcrypt-dev default-mysql-client samba smbclient libsmbclient-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && apt-get install -y --no-install-recommends dialog \
    && apt-get install -y --no-install-recommends openssh-server \
    && apt-get install -y --no-install-recommends apache2 libapache2-mod-fcgid \
    && docker-php-ext-install mysqli pdo pdo_mysql opcache gd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && pecl install smbclient \
    && docker-php-ext-enable pdo_mysql opcache smbclient gd

# COPIED FROM php8.2-apache <<COPIED
ENV APACHE_CONFDIR=/etc/apache2
ENV APACHE_ENVVARS=$APACHE_CONFDIR/envvars
RUN set -eux; \
# setup directories and permissions
	. "$APACHE_ENVVARS"; \
	for dir in \
		"$APACHE_LOCK_DIR" \
		"$APACHE_RUN_DIR" \
		"$APACHE_LOG_DIR" \
# https://salsa.debian.org/apache-team/apache2/-/commit/b97ca8714890ead1ba6c095699dde752e8433205
		"$APACHE_RUN_DIR/socks" \
	; do \
		rm -rvf "$dir"; \
		mkdir -p "$dir"; \
		chown "$APACHE_RUN_USER:$APACHE_RUN_GROUP" "$dir"; \
# allow running as an arbitrary user (https://github.com/docker-library/php/issues/743)
		chmod 1777 "$dir"; \
	done; \
# delete the "index.html" that installing Apache drops in here
	rm -rvf /var/www/html/*; \
	\
# logs should go to stdout / stderr
	ln -sfT /dev/stderr "$APACHE_LOG_DIR/error.log"; \
	ln -sfT /dev/stdout "$APACHE_LOG_DIR/access.log"; \
	ln -sfT /dev/stdout "$APACHE_LOG_DIR/other_vhosts_access.log"; \
	chown -R --no-dereference "$APACHE_RUN_USER:$APACHE_RUN_GROUP" "$APACHE_LOG_DIR"
#COPIED

RUN a2dismod mpm_prefork && a2enmod mpm_event
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod expires
RUN a2enmod cache
RUN a2enmod cache_disk
RUN a2enmod deflate
RUN a2enmod setenvif proxy proxy_fcgi

# PHP files should be handled by PHP, and should be preferred over any other file type
RUN { \
		echo '<FilesMatch \.php$>'; \
		echo '\tSetHandler application/x-httpd-php'; \
		echo '</FilesMatch>'; \
		echo; \
		echo 'DirectoryIndex disabled'; \
		echo 'DirectoryIndex index.php index.html'; \
		echo; \
		echo '<Directory /var/www/>'; \
		echo '\tOptions -Indexes'; \
		echo '\tAllowOverride All'; \
		echo '</Directory>'; \
	} | tee "$APACHE_CONFDIR/conf-available/docker-php.conf" \
	&& a2enconf docker-php

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

WORKDIR /var/www/html

ENV PORT=80
EXPOSE 80 2222

ENTRYPOINT [ "./entrypoint.sh" ]
