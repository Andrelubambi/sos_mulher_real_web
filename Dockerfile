FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    gnupg ca-certificates build-essential \
    supervisor nginx \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && pecl install redis && docker-php-ext-enable redis && \
    echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

# Instalar Composer e Laravel Echo Server
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
RUN npm install -g laravel-echo-server

# Configurar diretório de trabalho e copiar aplicação
WORKDIR /var/www/html
COPY . .

# Copiar configurações
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Instalar dependências e configurar permissões
RUN composer install --no-dev --optimize-autoloader \
    && npm ci && npm run build \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && mkdir -p /var/log/supervisor /var/www/html/storage/logs \
    && php artisan storage:link

# Health check para verificar se PHP-FPM está respondendo
HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:8000/health || exit 1

EXPOSE $PORT

# Script de inicialização
CMD sh -c " \
    sed -i 's/listen 8000;/listen $PORT;/g' /etc/nginx/conf.d/default.conf && \
    /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf -n \
"