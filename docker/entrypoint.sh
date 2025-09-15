#!/bin/bash
set -e

# Substituir porta do Railway no Nginx (se existir)
if [ -n "$PORT" ]; then
  sed -i "s/listen 8000;/listen ${PORT};/g" /etc/nginx/conf.d/default.conf
  sed -i "s/server_name _;/server_name _;\\n    listen ${PORT};/g" /etc/nginx/conf.d/default.conf
fi

# Rodar migrations (opcional, pode remover se não quiser auto-migrate)
php artisan migrate --force || true

exec "$@"
 