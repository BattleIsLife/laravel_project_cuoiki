FROM php:8.4-apache

# Cài extension...
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip unzip nano git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY docker/000-laravel.conf /etc/apache2/sites-available/000-laravel.conf

RUN a2dissite 000-default.conf && a2ensite 000-laravel.conf

# Cấu hình entry point
# COPY entrypoint.sh /usr/local/bin/entrypoint.sh
# RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html
COPY . .


# Copy Composer từ image chính thức (nếu cần composer trong runtime)
COPY --from=docker.io/library/composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage

# Migration
RUN php artisan key:generate && \
    php artisan migrate && \
    php artisan db:seed



EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]