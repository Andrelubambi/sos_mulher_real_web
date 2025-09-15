FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    gnupg ca-certificates build-essential \
    supervisor nginx \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && pecl install redis && docker-php-ext-enable redis && \
    echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
RUN npm install -g laravel-echo-server

WORKDIR /var/www/html
COPY . .

# CORREÇÃO AQUI ↓
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN composer install --no-dev --optimize-autoloader \
    && npm ci && npm run build \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && mkdir -p /var/log/supervisor /var/www/html/storage/logs

EXPOSE $PORT

CMD sh -c "sed -i 's/listen 8000;/listen $PORT;/g' /etc/nginx/conf.d/default.conf && /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf -n"