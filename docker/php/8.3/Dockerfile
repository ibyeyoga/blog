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

# 安装redis扩展
RUN pecl install redis && \
    docker-php-ext-enable redis

# 安装composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer


# 安装nodejs
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /usr/share/keyrings/nodesource-archive-keyring.gpg && \
    echo "deb [signed-by=/usr/share/keyrings/nodesource-archive-keyring.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
    apt-get update && \
    apt-get install -y nodejs

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm -f /var/log/lastlog /var/log/faillog

WORKDIR /var/www

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && \
    chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]

EXPOSE 9000