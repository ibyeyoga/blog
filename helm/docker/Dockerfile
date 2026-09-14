# ============================================================================
# blog 项目镜像
#
# 两个构建目标，都【一定包含 vendor】：
#
#   dev   = 运行环境 + vendor（含开发依赖）        ← 本地开发用
#           代码不打包，全部从宿主挂载。
#           带 dev 依赖的原因：本地要跑 php artisan test / tinker / pail，
#           而且宿主 bootstrap/cache 里的"包发现缓存"是按含 dev 依赖生成的，
#           vendor 必须与之匹配，否则会报 Class "Laravel\Pail\..." not found。
#
#   prod  = 运行环境 + vendor(no-dev) + 代码 + 前端产物   ← 模拟生产用
#
# 构建：
#   docker build --target dev  -t blog-php:dev  -f helm/docker/Dockerfile .
#   docker build --target prod -t blog-php:prod -f helm/docker/Dockerfile .
# 或跑 helm/docker/build.ps1
# ============================================================================

# ---------- 阶段 1a：vendor（本地开发，含开发依赖）----------
FROM docker.1ms.run/library/composer:2 AS vendor-dev
WORKDIR /app
COPY composer.json composer.lock ./
# packagist 官方源在国内极慢（含 dev 依赖有 100+ 个包，能跑十几分钟）。
# 换成腾讯云镜像，下载地址也由镜像改写，不再走 github。
RUN composer config -g repo.packagist composer https://mirrors.cloud.tencent.com/composer/
# --mount=type=cache 把下载缓存放在镜像层之外：
# 下次构建即使 composer.json 变了也只需下增量，不用从头再来一遍
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts

# ---------- 阶段 1b：vendor（生产，不含开发依赖）----------
FROM docker.1ms.run/library/composer:2 AS vendor-prod
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer config -g repo.packagist composer https://mirrors.cloud.tencent.com/composer/
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts

# ---------- 阶段 2：前端产物 ----------
FROM docker.1ms.run/library/node:20-alpine AS assets
WORKDIR /app
# .npmrc 一起拷进来，里面通常配了国内 npm 源
COPY package.json package-lock.json vite.config.js .npmrc ./
COPY resources/ resources/
COPY public/ public/
RUN --mount=type=cache,target=/root/.npm \
    npm ci && npm run build

# ---------- 阶段 3：运行环境骨架（dev / prod 共用）----------
FROM devx-php83-base:local AS runtime

WORKDIR /var/www/html

# Laravel 要求这些目录存在
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# EXPOSE / CMD / WORKDIR 都从 devx-php83-base 继承，这里不用重复

# ---------- 阶段 4：dev —— 运行环境 + 含 dev 依赖的 vendor ----------
FROM runtime AS dev

COPY --from=vendor-dev --chown=www-data:www-data /app/vendor /var/www/html/vendor/

# ---------- 阶段 5：prod —— 运行环境 + vendor + 代码 + 前端产物 ----------
FROM runtime AS prod

COPY --from=vendor-prod --chown=www-data:www-data /app/vendor /var/www/html/vendor/

# .dockerignore 排除了 vendor/ 和 bootstrap/cache/，
# 所以这一步不会覆盖上面装好的 vendor，也不会带进宿主的旧缓存
COPY --chown=www-data:www-data . /var/www/html/

COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build/
