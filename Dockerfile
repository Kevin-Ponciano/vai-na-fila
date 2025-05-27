# -------------------------------------------------------------------
#  Base ‒ PHP-FPM 8.3 + Debian Bullseye
# -------------------------------------------------------------------
ARG PHP_VERSION
FROM php:8.3.21-fpm-bullseye

# ---------------------------- ARGs ---------------------------------
ARG APP_DIR=/var/www/app
ARG LIVEWIRE_TEMP_DIR=/var/www/app/storage/app/livewire-tmp
ARG REDIS_LIB_VERSION=5.3.7

# --------------------- Pacotes de sistema --------------------------
RUN apt-get update -y && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
    apt-utils supervisor cron nano wget lsb-release gnupg2 unzip \
    libbrotli-dev zlib1g-dev libzip-dev libpng-dev libxml2-dev \
    default-libmysqlclient-dev default-mysql-client nginx

# ------------------ Extensões / PECL / Composer --------------------
RUN docker-php-ext-install mysqli pdo pdo_mysql session xml \
 && docker-php-ext-install zip iconv simplexml pcntl gd fileinfo \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install redis-${REDIS_LIB_VERSION} \
    && docker-php-ext-enable redis

# -------------------------- Node.js --------------------------------
RUN curl -sL https://deb.nodesource.com/setup_21.x | bash - \
 && apt-get install -y nodejs

# ------------------------ Configuração -----------------------------
COPY ./docker/php/extra-php.ini "$PHP_INI_DIR/99_extra.ini"
COPY ./docker/php/extra-php-fpm.conf /etc/php8/php-fpm.d/www.conf

# Supervisor: Octane + Reverb no mesmo arquivo
COPY ./docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf


# Nginx
RUN rm -rf /etc/nginx/sites-enabled/* /etc/nginx/sites-available/*
COPY ./docker/nginx/sites.conf /etc/nginx/sites-enabled/default.conf
COPY ./docker/nginx/error.html /var/www/html/error.html

# ---------------------- Código da aplicação ------------------------
WORKDIR ${APP_DIR}
COPY --chown=www-data:www-data . .
RUN chmod -R 777 bootstrap/cache
RUN chmod -R 777 storage

RUN mkdir -p ${LIVEWIRE_TEMP_DIR} \
 && chown -R www-data:www-data ${LIVEWIRE_TEMP_DIR} ${APP_DIR}

# Dependências de front / back
RUN npm install && npm run build \
 && composer install --no-interaction --no-dev --optimize-autoloader

# Caches
RUN php artisan config:cache   && \
    php artisan route:cache    && \
    php artisan view:cache     && \
    php artisan event:cache

# Cron (scheduler)
RUN echo "* * * * * cd ${APP_DIR} && php artisan schedule:run" > /etc/cron.d/laravel \
 && chmod 0644 /etc/cron.d/laravel \
 && crontab /etc/cron.d/laravel

# Limpeza
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# -------------------------- Entrypoint -----------------------------
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
