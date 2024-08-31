FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install zip mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY conf/apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY public/ /var/www/html/public/
COPY src/ /var/www/html/src/
COPY composer.json /var/www/html/
COPY composer.lock /var/www/html/

RUN composer install

EXPOSE 80
