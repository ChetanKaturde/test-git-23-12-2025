# Multi-stage Dockerfile for Monitorbizz Laravel Application
# ========================================================

# Base stage: PHP 8.1 with essential extensions
FROM php:8.1-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    gmp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        gmp \
        soap \
        opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (without dev dependencies for production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Frontend stage: Build Node.js assets
FROM node:18-alpine AS frontend

WORKDIR /app

# Copy package files
COPY package.json package-lock.json ./

# Install Node.js dependencies
RUN npm ci --only=production

# Copy resources
COPY resources/ ./resources/
COPY public/ ./public/
COPY vite.config.js ./

# Build assets
RUN npm run build

# Production stage: Combine PHP app with built assets
FROM base AS production

# Copy built assets from frontend stage
COPY --from=frontend /app/public/build ./public/build

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf

# Configure PHP-FPM
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php.ini /usr/local/etc/php/php.ini

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisord.conf

# Create log directories
RUN mkdir -p /var/log/nginx /var/log/php-fpm /var/log/supervisor

# Expose ports
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Start Supervisor (manages Nginx and PHP-FPM)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]