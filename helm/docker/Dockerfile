FROM composer:2 AS composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts

FROM node:20-alpine AS node
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources/ resources/
COPY public/ public/
RUN npm ci && npm run build

FROM laradock/php-fpm:20250726-8.3

USER root

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libcurl4-openssl-dev \
        libxml2-dev \
        libssl-dev \
        unzip \
        git \
        gnupg \
        ca-certificates \
        curl

RUN docker-php-ext-install -j$(nproc) zip exif bcmath sockets pcntl

RUN pecl install redis && \
    docker-php-ext-enable redis

WORKDIR /var/www/html

# 复制 代码
COPY --chown=www-data:www-data . /var/www/html/

# 复制 composer 安装的依赖包
COPY --from=composer --chown=www-data:www-data /app/vendor /var/www/html/vendor/

# 复制 前端build后的资源
COPY --from=node --chown=www-data:www-data /app/public/build /var/www/html/public/build/

# 确保 storage 和 bootstrap/cache 可写
RUN mkdir -p bootstrap/cache && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
