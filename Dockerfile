# Imagen de producción: Laravel servido por FrankenPHP (Caddy con HTTPS automático).

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
# Tailwind escanea las vistas de paginación de Laravel (tailwind.config.js).
COPY --from=vendor /app/vendor/laravel/framework/src/Illuminate/Pagination/resources/views vendor/laravel/framework/src/Illuminate/Pagination/resources/views
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

FROM dunglas/frankenphp:1-php8.3
RUN install-php-extensions pdo_mysql opcache \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && printf 'upload_max_filesize=4M\npost_max_size=16M\n' > "$PHP_INI_DIR/conf.d/uploads.ini"
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY --from=vendor /app/vendor vendor
COPY . .
COPY --from=assets /app/public/build public/build
RUN composer dump-autoload --optimize --no-dev && php artisan storage:link

EXPOSE 80 443 443/udp
CMD ["sh", "-c", "php artisan migrate --force && php artisan optimize && exec frankenphp run --config /app/docker/Caddyfile"]
