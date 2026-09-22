FROM dunglas/frankenphp:php8.4

RUN install-php-extensions \
    pdo_mysql pdo_sqlite intl gd zip bcmath exif pcntl sockets opcache redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/upload.ini $PHP_INI_DIR/conf.d/upload.ini

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

COPY . .

# Rahasia tidak ikut masuk image (lihat .dockerignore); nilainya datang dari
# env_file compose. Dotenv tetap butuh berkasnya ada, dan berkas kosong
# tidak menimpa variabel lingkungan yang sudah diset.
RUN touch .env && \
    composer dump-autoload --optimize && \
    mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache

ENV OCTANE_SERVER=frankenphp
EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
