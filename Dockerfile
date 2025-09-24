FROM php:8.2-fpm-alpine

# Dependências do sistema e build
RUN apk add --no-cache \
    oniguruma-dev libxml2-dev build-base \
    freetype-dev libpng-dev libjpeg-turbo-dev \
    nodejs npm \
    netcat-openbsd \
    redis-cli \
    && rm -rf /var/cache/apk/*

# Instalar extensões PHP (inclui GD com JPEG/Freetype)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Diretório de trabalho (alinhado com docker-compose)
WORKDIR /var/www

# Copiar arquivos do projeto    
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# NOVAS LINHAS PARA RESOLVER O VITE:
RUN npm install
RUN npm run build

# Ajustar permissões (corrigido para /var/www)
RUN mkdir -p /var/log/php-fpm \
 && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/log/php-fpm \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Laravel Echo Server
RUN npm install -g laravel-echo-server
# Copiar o arquivo de configuração para o diretório correto
COPY laravel-echo-server.json /var/www/laravel-echo-server.json

# Porta do Nginx
EXPOSE 80
