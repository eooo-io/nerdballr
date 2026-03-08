###############################################################################
# FFIS — PHP 8.3 / Laravel 11 Image
###############################################################################

FROM php:8.3-fpm-alpine

# ─── System dependencies ──────────────────────────────────────────────────

RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    mariadb-client \
    oniguruma-dev \
    shadow \
    unzip \
    zip

# ─── PHP extensions ───────────────────────────────────────────────────────

RUN docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) \
    bcmath \
    gd \
    mbstring \
    pdo \
    pdo_mysql \
    pcntl \
    zip \
    opcache

# Redis extension via PECL
RUN pecl install redis \
 && docker-php-ext-enable redis

# ─── Composer ─────────────────────────────────────────────────────────────

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ─── Working directory ────────────────────────────────────────────────────

WORKDIR /var/www/html

# ─── Default command ──────────────────────────────────────────────────────

CMD ["php-fpm"]

EXPOSE 9000
