FROM composer:1.10 AS composer

ADD composer.* ./
ADD src/ src

RUN composer global require hirak/prestissimo --no-plugins --no-scripts
RUN composer install --optimize-autoloader --prefer-dist --no-progress --no-interaction --ignore-platform-reqs

FROM php:7.2.34-cli-alpine3.12 AS phuml
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
