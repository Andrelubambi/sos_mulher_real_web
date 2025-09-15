#!/bin/bash
set -e

# Substituir porta do Railway no Nginx (se existir)
if [ -n "$PORT" ]; then
  sed -i "s/listen 8000;/listen ${PORT};/" /etc/nginx/conf.d/default.conf
fi

# Rodar migrations (opcional, pode remover se não quiser auto-migrate)
php artisan migrate --force || true

exec "$@"
 