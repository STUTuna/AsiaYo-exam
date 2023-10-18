FROM php:8.2-fpm

# 安裝系統依賴和 PHP 擴展
RUN apt-get update && apt-get install -y zip git unzip && \
    docker-php-ext-install pdo pdo_mysql

# 安裝 Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY . /var/www

RUN composer install
