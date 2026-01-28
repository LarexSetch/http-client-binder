FROM php:8.4-cli

RUN apt-get update && \
    apt-get install -y wget git curl

WORKDIR /opt/project

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer --2 && \
    php -r "unlink('composer-setup.php');"

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
