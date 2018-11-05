#!/bin/bash

PHP_INI="/etc/php7/php.ini"
NGINX_CONF="/etc/nginx/conf.d/default.conf"

if [ ! -z "$PORT" ]; then
    echo "Setting web port number to ${PORT} ..."
    sed -i "s/listen 8080;/listen ${PORT};/" $NGINX_CONF
fi

if [ ! -z "$SERVER_NAME" ]; then
    echo "Setting Nginx server_name to ${SERVER_NAME} ..."
    sed -i "s/server_name _;/server_name ${SERVER_NAME};/" $NGINX_CONF
fi

if [ ! -z "$XDEBUG_CONFIG" ]; then
    echo "Enabling Xdebug ..."
    echo "zend_extension=/usr/lib/php7/modules/xdebug.so" >> $PHP_INI
    echo "xdebug.remote_enable=1 " >> $PHP_INI
    echo "xdebug.remote_log=/tmp/xdebug.log"  >> $PHP_INI
    echo "xdebug.remote_autostart=1" >> $PHP_INI
    # xdebug.remote_host is set through XDEBUG_CONF, eg. XDEBUG_CONFIG=remote_host=192.168.1.5.
    # use PHP_IDE_CONFIG=serverName=docker to set server name.
fi

# "/var/tmp/nginx" owned by "nginx" user is unusable on heroku dyno so re-create on runtime
mkdir /var/tmp/nginx

supervisord --nodaemon --configuration /etc/supervisord.conf -j /tmp/supervisord.pid
