FROM ubuntu:trusty

RUN apt-get update -y
RUN apt-get install -y software-properties-common python-software-properties
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update -y
RUN apt-get install -y graphviz
RUN apt-get install -y pkg-config
RUN apt-get -y install libmagickwand-dev imagemagick php7.1 php7.1-dev php7.1-xml php7.1-xdebug
RUN yes | pecl install imagick
RUN echo "extension=imagick.so" > /etc/php/7.1/mods-available/imagick.ini
RUN ln -s /etc/php/7.1/mods-available/imagick.ini /etc/php/7.1/cli/conf.d/20-imagick.ini
RUN mkdir -p /usr/src/phuml

WORKDIR /usr/src/phuml
