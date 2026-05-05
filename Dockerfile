FROM serversideup/php:8.4-fpm-nginx

USER root

RUN install-php-extensions intl
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini

USER www-data
