FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    icu-dev \
    libzip-dev \
    linux-headers \
    oniguruma-dev \
    postgresql-dev \
    unzip \
    zip \
    $PHPIZE_DEPS \
    && docker-php-ext-install bcmath intl opcache pcntl pdo_pgsql zip \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY php.ini /usr/local/etc/php/conf.d/dayai.ini
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY entrypoint.sh /usr/local/bin/dayai-entrypoint

RUN chmod +x /usr/local/bin/dayai-entrypoint

WORKDIR /var/www/html

ENTRYPOINT ["dayai-entrypoint"]
CMD ["php-fpm"]
