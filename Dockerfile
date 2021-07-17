FROM composer:2.1.3 AS composer

ADD composer.* ./
ADD src/ src

RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-progress --no-interaction --ignore-platform-reqs --no-plugins

FROM php:7.4.20-cli-alpine3.14 AS phuml
MAINTAINER Luis Montealegre <montealegreluis@gmail.com>

RUN apk add --update --no-cache autoconf g++ pkgconfig imagemagick imagemagick-dev make ttf-freefont graphviz \
    && printf "\n" | pecl install imagick \
    && echo "extension=imagick.so" >> /usr/local/etc/php/php.ini

WORKDIR /app

ADD bin/phuml* bin/
ADD src/ src

ENV PATH="/app/bin:${PATH}"

COPY --from=composer /app/vendor/ ./vendor

WORKDIR /code

ENTRYPOINT ["phuml"]
