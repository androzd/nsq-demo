FROM php:7.3.12-fpm

RUN apt-get update -yqq && \
    apt-get install -yqq \
      libmemcached-dev \
      zlib1g-dev \
      wget \
      unzip \
      libzip-dev \
      libevent-dev \
      libgmp-dev \
      zlib1g-dev \
      libpng-dev \
      vim \
      procps && \
      apt-get clean && \
      pecl install \
      memcached-3.1.3 \
      xdebug-2.7.0beta1 && \
    docker-php-ext-enable \
      memcached \
      xdebug

RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install bcmath    && \
    docker-php-ext-install gmp       && \
    docker-php-ext-install gd        && \
    docker-php-ext-install zip

COPY php.dev.ini /usr/local/etc/php/php.ini
COPY nsq.so /usr/local/lib/php/extensions/no-debug-non-zts-20180731

WORKDIR /var/www/html

RUN chown -R 1000.1000 /var/www/html

