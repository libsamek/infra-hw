FROM alpine:3.8

ENV APPDATA /www
ENV PHP_INI_DIR /etc/php7
ENV NGINX_CONFD_DIR /etc/nginx/conf.d
ENV USR nginx
ENV GRP nginx

WORKDIR $APPDATA

RUN \
    apk update \
    \
    && apk add bash \
    && apk add curl \
    # install php
    && apk add php7 \
    && apk add php7-apcu \
    && apk add php7-ctype \
    && apk add php7-curl \
    && apk add php7-dom \
    && apk add php7-fileinfo \
    && apk add php7-ftp \
    && apk add php7-iconv \
    && apk add php7-intl \
    && apk add php7-json \
    && apk add php7-mbstring \
    && apk add php7-mcrypt \
    && apk add php7-mysqlnd \
    && apk add php7-opcache \
    && apk add php7-openssl \
    && apk add php7-pdo \
    && apk add php7-pdo_sqlite \
    && apk add php7-phar \
    && apk add php7-posix \
    && apk add php7-session \
    && apk add php7-simplexml \
    && apk add php7-sqlite3 \
    && apk add php7-tokenizer \
    && apk add php7-xml \
    && apk add php7-xmlreader \
    && apk add php7-xmlwriter \
    && apk add php7-zlib \
    && apk add php7-xdebug \
    \
    # install php-fpm
    && apk add php7-fpm \
    # make php-fpm listen to not tcp port but unix socket
    && sed -i -E "s/127\.0\.0\.1:9000/\/var\/run\/php-fpm\/php-fpm.sock/" /etc/php7/php-fpm.d/www.conf \
    \
    && sed -i "s/user = nobody/;user = nobody/" /etc/php7/php-fpm.d/www.conf \
    && sed -i "s/group = nobody/;group = nobody/" /etc/php7/php-fpm.d/www.conf \
    && sed -i "s/;clear_env = no/clear_env = no/" /etc/php7/php-fpm.d/www.conf \
    && mkdir /var/run/php-fpm \
    \
    # install nginx and create default pid directory
    && apk add nginx \
    && mkdir -p /run/nginx \
    \
    && sed -i "s/^user nginx;/#user nginx;/" /etc/nginx/nginx.conf \
    \
    # forward nginx logs to docker log collector
    && sed -i -E "s/error_log .+/error_log \/dev\/stderr warn;/" /etc/nginx/nginx.conf \
    && sed -i -E "s/access_log .+/access_log \/dev\/stdout main;/" /etc/nginx/nginx.conf \
    \
    # install supervisor
    && apk add supervisor \
    && mkdir -p /etc/supervisor.d/ \
    \
    # remove caches to decrease image size
    && rm -rf /var/cache/apk/* \
    \
    # install composer
    && EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig) \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '$EXPECTED_SIGNATURE') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

COPY sys/php.ini $PHP_INI_DIR/
COPY sys/nginx.conf $NGINX_CONFD_DIR/default.conf
COPY sys/supervisor.programs.ini /etc/supervisor.d/
COPY sys/start.sh /start.sh

RUN \
    # @see https://devcenter.heroku.com/articles/container-registry-and-runtime#run-the-image-as-a-non-root-user
    \
    # followings are just for local environment
    # (on heroku dyno there is no permission problem because most of the filesystem owned by the current non-root user)
    chown $USR:$GRP /start.sh \
    \
    # to update conf files and create temp files under the directory via sed command on runtime
    && chown -R $USR:$GRP /etc/php7/php-fpm.d \
    && chown -R $USR:$GRP /etc/php7/php.ini \
    && chown -R $USR:$GRP /etc/nginx \
    \
    # to run php-fpm (socker directory)
    && chown -R $USR:$GRP /var/run/php-fpm \
    \
    # to run nginx (default pid directory and tmp directory)
    && chown -R $USR:$GRP /run/nginx \
    && chown -R $USR:$GRP /var/tmp/nginx \
    \
    # to run supervisor (read conf and create socket)
    && chown -R $USR:$GRP /etc/supervisor* \
    && sed -i -E "s/^file=\/run\/supervisord\.sock/file=\/run\/supervisord\/supervisord.conf/" /etc/supervisord.conf \
    && mkdir -p /run/supervisord \
    && chown -R $USR:$GRP /run/supervisord \
    \
    # to output logs
    && chown -R $USR:$GRP /var/log \
    \
    # appdata dir
    && chown -R $USR:$GRP $APPDATA \
    # add nonroot to sudoers
    && apk add --no-cache sudo \
    && echo "$USR ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

USER $USR

ONBUILD COPY src/ $APPDATA/src/
ONBUILD COPY composer.* $APPDATA/

ONBUILD RUN composer install --no-interaction

EXPOSE 8080

CMD ["/start.sh"]
