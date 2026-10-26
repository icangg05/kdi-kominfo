# Aset situs publik (Tailwind + skrip) dibangun Vite di tahap terpisah; image akhir tidak membawa Node.
FROM node:24-alpine AS aset
WORKDIR /srv
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
# Tailwind memindai view dan komponen Livewire untuk kelas yang dipakai (lihat @source di situs.css).
COPY resources ./resources
COPY app/Livewire ./app/Livewire
RUN npm run build

FROM dunglas/frankenphp:php8.4

RUN install-php-extensions \
    pdo_mysql pdo_sqlite intl gd zip bcmath exif pcntl sockets opcache redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/upload.ini $PHP_INI_DIR/conf.d/upload.ini

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

COPY . .
COPY --from=aset /srv/public/build public/build

# Rahasia tidak ikut masuk image (lihat .dockerignore); nilainya datang dari
# env_file compose. Dotenv tetap butuh berkasnya ada, dan berkas kosong
# tidak menimpa variabel lingkungan yang sudah diset.
RUN touch .env && \
    composer dump-autoload --optimize && \
    mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache && \
    ln -sfn ../storage/app/public public/storage && \
    chmod -R 777 storage bootstrap/cache

ENV OCTANE_SERVER=frankenphp
EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
