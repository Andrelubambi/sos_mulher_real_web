FROM php:8.2-fpm

# Instalar dependências do sistema incluindo Node.js
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    gnupg ca-certificates \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar tudo
COPY . .

# Instalar dependências Laravel e NPM
RUN composer install --no-dev --optimize-autoloader \
    && npm install

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]