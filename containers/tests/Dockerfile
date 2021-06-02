FROM ubuntu:xenial

RUN apt-get update -y && apt-get install -y software-properties-common python-software-properties
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update -y && apt-get install -y git graphviz pkg-config libmagickwand-dev imagemagick php7.1 php7.1-cli php7.1-common php7.1-dev php7.1-xml php7.1-xdebug php7.1-imagick
RUN mkdir -p /usr/src/phuml

WORKDIR /usr/src/phuml
