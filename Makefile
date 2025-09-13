# Makefile Laravel Docker (somente DEV local)

APP_ENV=dev
STACK_NAME=laravel_app

# Deploy com rollback
up:
	@echo "🔹 Deploy iniciado em modo DEV..."
	@docker compose --env-file .env.dev -f docker-compose.dev.yml up -d --build || { \
		echo "❌ Deploy falhou, rollback..."; \
		docker compose -f docker-compose.dev.yml down; \
		exit 1; \
	}
	@echo "✅ Deploy concluído!"
	$(MAKE) slack_notify

# Parar containers
down:
	docker compose -f docker-compose.dev.yml down

# Reiniciar
restart:
	$(MAKE) down
	$(MAKE) up

# Logs
logs:
	docker compose -f docker-compose.dev.yml logs -f

# Notificação (opcional)
slack_notify:
	@echo "🔔 Deploy DEV concluído!"

# Build cache Docker
build-cache:
	docker build --cache-from $(STACK_NAME) -t $(STACK_NAME) .
