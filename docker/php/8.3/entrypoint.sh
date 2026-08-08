#!/bin/bash
set -e

APP_BASE="${APP_BASE:-/var/www/html}"

if [ -n "$APP_PROJECTS" ]; then
    IFS=',' read -ra PROJECTS <<< "$APP_PROJECTS"
else
    PROJECTS=("blog") # 多个项目用空格隔开
fi

WRITABLE_DIRS=("storage" "bootstrap/cache" "public/uploads")

if [ -n "$PGID" ] && [ "$PGID" != "1000" ]; then
    groupmod -o -g "$PGID" www-data
fi
if [ -n "$PUID" ] && [ "$PUID" != "1000" ]; then
    usermod -o -u "$PUID" -g www-data www-data
fi

for proj in "${PROJECTS[@]}"; do
    proj_path="${APP_BASE}/${proj}"

    # 项目目录挂载后必定存在，直接设置根目录所有权
    chown www-data:www-data "$proj_path"

    # 对需要写权限的子目录递归设置所有权
    for sub in "${WRITABLE_DIRS[@]}"; do
        sub_path="${proj_path}/${sub}"
        if [ -d "$sub_path" ]; then
            chown -R www-data:www-data "$sub_path"
        fi
    done
done

composer config -g repo.packagist composer https://mirrors.cloud.tencent.com/composer/

exec "$@"