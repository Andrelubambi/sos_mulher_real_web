# Use uma imagem base Alpine (mais leve)
FROM php:8.2-fpm-alpine

# Dependências do sistema e build
RUN apk add --no-cache \
    git curl zip unzip supervisor nginx bash \
    oniguruma-dev libxml2-dev build-base \
    freetype-dev libpng-dev libjpeg-turbo-dev \
    nodejs npm

# Instalar extensões PHP (inclui GD com JPEG/Freetype)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões
RUN mkdir -p /var/log/php-fpm \
 && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/log/php-fpm \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configurações Nginx, Supervisor e Entrypoint
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx.main.conf /etc/nginx/nginx.conf
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Laravel Echo Server
RUN npm install -g laravel-echo-server
COPY laravel-echo-server.json /var/www/html/laravel-echo-server.json

# Porta do Nginx
EXPOSE 8000

# Entrypoint + processo principal (supervisord gerencia nginx/php-fpm/queues)
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisor.conf"]