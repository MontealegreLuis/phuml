FROM php:7.4.20-cli-alpine3.12

RUN apk add --update --no-cache autoconf g++ pkgconfig imagemagick imagemagick-dev make ttf-freefont graphviz \
    && printf "\n" | pecl install imagick \
    && echo "extension=imagick.so" >> /usr/local/etc/php/php.ini

RUN mkdir -p /usr/src/phuml

WORKDIR /usr/src/phuml
