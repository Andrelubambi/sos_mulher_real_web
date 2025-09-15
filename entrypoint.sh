#!/bin/bash
set -e

# Substitui listen 8000 pelo PORT fornecido pela plataforma (se existir)
if [ -n "$PORT" ]; then
    sed -i "s/listen 8000;/listen ${PORT};/g" /etc/nginx/conf.d/default.conf
fi

# Configurar Redis se as variáveis existirem (apenas substitui se estiver definido)
if [ -n "$REDISHOST" ]; then
    sed -i "s/REDIS_HOST=.*/REDIS_HOST=${REDISHOST}/" /var/www/html/.env || true
    sed -i "s/REDIS_PASSWORD=.*/REDIS_PASSWORD=${REDISPASSWORD}/" /var/www/html/.env || true
    sed -i "s/REDIS_PORT=.*/REDIS_PORT=${REDISPORT}/" /var/www/html/.env || true
fi

# Opcional: atualizar APP_URL se disponível
if [ -n "$APP_URL" ]; then
    sed -i "s|APP_URL=.*|APP_URL=${APP_URL}|" /var/www/html/.env || true
fi

# Ajustes de permissões mínimos (garante logs graváveis)
chown -R www-data:www-data /var/www/html || true
mkdir -p /var/log/supervisor

# Iniciar Supervisor (mantém em foreground)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf -n
