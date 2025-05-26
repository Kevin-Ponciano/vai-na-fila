# ────────────────────────────────────────────────────────────────
#   Base image ‒ PHP-FPM 8.3 + Debian Bullseye
# ────────────────────────────────────────────────────────────────
FROM php:8.4.7-bullseye

# ──────────────── Args / paths ────────────────
ARG APP_DIR=/var/www/app
ARG REDIS_LIB_VERSION=5.3.7

# ──────────────── OS packages ────────────────
RUN apt-get update -y && \
    apt-get install -y --no-install-recommends \
        apt-utils supervisor cron nano wget gnupg2 lsb-release unzip \
        libbrotli-dev zlib1g-dev libzip-dev libpng-dev libpq-dev \
        libxml2-dev libssl-dev libonig-dev nginx postgresql-client-16

# ──────────────── PostgreSQL repo key ─────────
RUN echo "deb http://apt.postgresql.org/pub/repos/apt \
         $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list && \
    curl -fsSL https://www.postgresql.org/media/keys/ACCC4CF8.asc | \
         gpg --dearmor -o /etc/apt/trusted.gpg.d/postgresql.gpg

# ──────────────── PHP extensions ──────────────
RUN docker-php-ext-install \
        mysqli pdo pdo_mysql pdo_pgsql pgsql \
        session xml sockets zip iconv simplexml pcntl gd fileinfo

# Redis & Swoole via PECL
RUN pecl install redis-${REDIS_LIB_VERSION} && \
    docker-php-ext-enable redis && \
    pecl install swoole && \
    docker-php-ext-enable swoole

# ──────────────── Composer & Node ─────────────
RUN curl -sS https://getcomposer.org/installer | \
        php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sL https://deb.nodesource.com/setup_21.x -o nodesource_setup.sh && \
    bash nodesource_setup.sh && \
    apt-get install -y nodejs && \
    rm nodesource_setup.sh

# ──────────────── App code ────────────────────
WORKDIR ${APP_DIR}
COPY --chown=www-data:www-data . .

# permissões para arquivo temporário Livewire
RUN mkdir -p ${LIVEWIRE_TEMP_DIR} && \
    chown -R www-data:www-data ${LIVEWIRE_TEMP_DIR}

# ──────────────── Dependências app ────────────
RUN composer install --no-interaction --no-dev --optimize-autoloader
RUN npm ci && npm run build

# ──────────────── Laravel comandos build ─────
RUN php artisan octane:install --server=swoole --no-interaction && \
    php artisan reverb:install --force && \
    php artisan config:cache   && \
    php artisan route:cache    && \
    php artisan view:cache     && \
    php artisan event:cache

# ──────────────── Cron (schedule:run fallback) ─
RUN echo "* * * * * cd ${APP_DIR} && php artisan schedule:run >> /dev/null 2>&1" \
      > /etc/cron.d/laravel && chmod 0644 /etc/cron.d/laravel && crontab /etc/cron.d/laravel

# ──────────────── Nginx site & Supervisor conf ─
COPY docker/nginx/sites.octane.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ──────────────── Clean up ────────────────────
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# ──────────────── Expose ports ────────────────
EXPOSE 80 8000 8080

CMD ["/usr/bin/supervisord","-c","/etc/supervisor/conf.d/supervisord.conf"]
