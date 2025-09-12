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
    && npm ci && npm run build

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Limpar cache (para garantir que a nova configuração seja aplicada)
RUN php artisan config:clear && php artisan cache:clear

# Expor a porta do Docker (isso é apenas informativo)
EXPOSE 8000

# Comando para iniciar o servidor, usando a variável de ambiente $PORT do Railway
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port", "${PORT}"]
