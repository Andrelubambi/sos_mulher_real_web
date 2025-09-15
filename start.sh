#!/bin/bash

# Configurar Nginx
if [ -n "$PORT" ]; then
    sed -i "s/listen 8000;/listen $PORT;/g" /etc/nginx/conf.d/default.conf
fi

# Configurar Redis se as variáveis existirem
if [ -n "$REDISHOST" ]; then
    sed -i "s/REDIS_HOST=.*/REDIS_HOST=${REDISHOST}/" /var/www/html/.env
    sed -i "s/REDIS_PASSWORD=.*/REDIS_PASSWORD=${REDISPASSWORD}/" /var/www/html/.env
    sed -i "s/REDIS_PORT=.*/REDIS_PORT=${REDISPORT}/" /var/www/html/.env
fi

# Iniciar Supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf -n