# ============================================================================
# blog 项目镜像
#
# 两个构建目标，都【一定包含 vendor】：
#
#   dev   = 基础镜像 + vendor                     ← 本地开发用
#           代码不打包，全部从宿主挂载。这样如果忘了挂某个目录，
#           会直接报"文件不存在"，而不是静默使用镜像里的旧代码。
#
#   prod  = dev + 业务代码 + 前端产物              ← 模拟生产用
#           生产环境代码也在镜像里，不依赖宿主。
#
# 构建：
#   docker build --target dev  -t blog-php:dev  -f helm/docker/Dockerfile .
#   docker build --target prod -t blog-php:prod -f helm/docker/Dockerfile .
# 或直接跑 devx/helm/images/build.ps1（两个都建）
# ============================================================================

# ---------- 阶段 1：composer 依赖 ----------
FROM docker.1ms.run/library/composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts

# ---------- 阶段 2：前端产物 ----------
FROM docker.1ms.run/library/node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources/ resources/
COPY public/ public/
RUN npm ci && npm run build

# ---------- 阶段 3：dev —— 基础镜像 + vendor，不含业务代码 ----------
FROM devx-php83-base:local AS dev

WORKDIR /var/www/html

COPY --from=vendor --chown=www-data:www-data /app/vendor /var/www/html/vendor/

# Laravel 要求这些目录存在。
# 本地模式下 storage 通常会被宿主挂载覆盖，所以这里主要是给
# image 模式（prod 继承）兜底。
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

# ---------- 阶段 4：prod —— dev + 业务代码 + 前端产物 ----------
FROM dev AS prod

# .dockerignore 里排除了 vendor/，所以这一步不会覆盖上面装好的 vendor
COPY --chown=www-data:www-data . /var/www/html/

COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build/
