# Base PHP 8.2 FPM
FROM php:8.2-fpm

# Instalar dependências do sistema e ferramentas
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    gnupg ca-certificates build-essential \
    supervisor \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos do projeto
COPY . .

# Instalar dependências PHP e Node.js
RUN composer install --no-dev --optimize-autoloader \
    && npm ci && npm run build

# Configurar Supervisor para gerenciar os processos
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Limpar cache e otimizar
RUN php artisan config:clear && php artisan cache:clear && php artisan optimize

# Expor a porta 8000 para a aplicação web e 6001 para o Laravel Echo Server
EXPOSE 8000
EXPOSE 6001

# Comando principal: Iniciar o Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisor.conf", "-n"]