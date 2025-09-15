FROM php:8.2-fpm

# Instalar dependências do sistema (inclui curl explicitamente)
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    gnupg ca-certificates build-essential \
    supervisor nginx \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && pecl install redis && docker-php-ext-enable redis \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer e Laravel Echo Server
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
RUN npm install -g laravel-echo-server@1.6.1

# Configurar diretório de trabalho e copiar aplicação
WORKDIR /var/www/html
COPY . .

# Copiar configurações (se já tiveres os ficheiros no repo)
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Instalar dependências da aplicação, build do frontend, e permissões
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && npm ci --silent \
    && npm run build --silent || true \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && mkdir -p /var/log/supervisor /var/www/html/storage/logs \
    && php artisan storage:link || true

# Health check (usa curl, por isso garantimos a instalação acima)
HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
    CMD curl -fsS http://127.0.0.1:8000/health || exit 1

# EXPOSE não expande variáveis; mantemos a porta 'interna' padrão 8000
EXPOSE 8000

# Entrypoint — ao iniciar substitui a porta e lança supervisord
CMD ["/usr/local/bin/entrypoint.sh"]
