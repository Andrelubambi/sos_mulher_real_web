# Base PHP 8.2 FPM
FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    gnupg ca-certificates build-essential \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar todos os arquivos do projeto
COPY . .

# Instalar dependências PHP e Node
RUN composer install --no-dev --optimize-autoloader \
    && npm ci

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Limpar cache
RUN php artisan config:clear \
    && php artisan cache:clear

# Expor porta do PHP-FPM
EXPOSE 9000

# Comando que verifica APP_KEY e inicia o PHP-FPM
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php-fpm