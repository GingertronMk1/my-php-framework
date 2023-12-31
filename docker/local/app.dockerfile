FROM php:8.2-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apk add --no-cache linux-headers

RUN docker-php-ext-install sockets

RUN apk --update --no-cache add \
    $PHPIZE_DEPS \
    linux-headers \
    bash \
    && \
    pecl install -f xdebug-3.2.1 && \
    docker-php-ext-enable xdebug

WORKDIR /var/www

COPY . .
