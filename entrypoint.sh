#!/bin/sh
set -e
# Kiểm tra đã tồn tại file chưa, nếu chưa thì bắt đầu tiến hành cài đặt
if [ -z "$(ls -A /var/www/html/vendor)" ]; then
    echo "Tiến hành cài đặt dependencies"
    composer install --no-dev --optimize-autoloader

    # Migration
    echo "Tiến hành migration"
    php artisan migrate --force
    php artisan db:seed --force
fi

chown -R www-data:www-data /var/www/html/storage

exec "$@"