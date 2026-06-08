FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG BUILD_ENV=production

# Install system dependencies + Node.js 20 (NodeSource)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libzip-dev \
    libicu-dev \
    libexif-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy package files dulu untuk cache npm layer
# (jika package.json tidak berubah, layer ini di-cache)
COPY package*.json ./
RUN if [ -f "package.json" ]; then npm install; fi

# Copy composer files dulu untuk cache composer layer
COPY composer.json composer.lock ./
RUN if [ -f "composer.json" ]; then composer install --optimize-autoloader --no-dev --no-scripts; fi

# Copy semua source code
COPY . /var/www/html

# Build frontend assets
RUN if [ -f "package.json" ]; then npm run build; fi

# Run composer scripts setelah semua file ada
RUN if [ -f "composer.json" ]; then composer run-script post-autoload-dump 2>/dev/null || true; fi

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# Copy entrypoint script
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]