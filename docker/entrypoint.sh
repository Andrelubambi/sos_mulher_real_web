#!/bin/bash
set -e

# Substituir porta do Railway no Nginx (se existir)
if [ -n "$PORT" ]; then
  echo "Setting Nginx to listen on port: $PORT"
  # Substitui a porta 8000 pela PORT
  sed -i "s/listen 8000;/listen $PORT;/g" /etc/nginx/conf.d/default.conf
  # Adiciona a listen directive se não existir
  if ! grep -q "listen $PORT;" /etc/nginx/conf.d/default.conf; then
    sed -i "/server_name _;/a\    listen $PORT;" /etc/nginx/conf.d/default.conf
  fi
fi

# Corrigir permissões para PHP-FPM
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Rodar migrations
php artisan migrate --force || true

echo "Starting PHP-FPM directly to test..."
docker-php-entrypoint php-fpm &
sleep 3  # Aguarda o PHP-FPM iniciar

# No entrypoint.sh, antes do exec "$@"
echo "Checking if PHP-FPM is alive..."
curl -f http://localhost:9000/ping || echo "PHP-FPM not responding"

echo "Checking if Nginx is alive..."
curl -f http://localhost:8000/health || echo "Nginx not responding"

exec "$@"