#!/bin/sh
set -e

if [ -d /var/www/html ]; then
    cd /var/www/html

    mkdir -p \
        bootstrap/cache \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs

    chown -R www-data:www-data bootstrap/cache storage 2>/dev/null || true
    chmod -R ug+rwX bootstrap/cache storage 2>/dev/null || true
fi

exec docker-php-entrypoint "$@"
