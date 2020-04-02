FROM php:7.4-fpm

RUN apt-get update && apt-get install -y libpq-dev zlib1g-dev libicu-dev zip git \
    && docker-php-ext-configure intl && docker-php-ext-install pdo pdo_mysql intl mysqli

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY php.ini "$PHP_INI_DIR/php.ini"

USER 1000

