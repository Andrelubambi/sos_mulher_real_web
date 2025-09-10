# Makefile Laravel Docker
# Suporta: dev, staging, prod

# Comando padrão: dev
APP_ENV ?= dev
export $(shell sed 's/=.*//' .env.$(APP_ENV))

# Nome da stack Docker
STACK_NAME=laravel_app

# Deploy com rollback
up:
	@echo "🔹 Deploy iniciado para $(APP_ENV)..."
	@if [ "$(APP_ENV)" = "prod" ]; then \
		docker compose --env-file .env.prod -f docker-compose.yml -f docker-compose.prod.yml up -d --build || { \
			echo "❌ Deploy falhou, rollback..."; \
			docker compose down; \
			exit 1; \
		}; \
	elif [ "$(APP_ENV)" = "staging" ]; then \
		docker compose --env-file .env.staging -f docker-compose.yml -f docker-compose.staging.yml up -d --build || { \
			echo "❌ Deploy falhou, rollback..."; \
			docker compose down; \
			exit 1; \
		}; \
	else \
		docker compose --env-file .env.dev -f docker-compose.yml -f docker-compose.override.yml up -d --build || { \
			echo "❌ Deploy falhou, rollback..."; \
			docker compose down; \
			exit 1; \
		}; \
	fi
	@echo "✅ Deploy concluído!"
	$(MAKE) slack_notify

# Parar containers
down:
	docker compose down

# Reiniciar
restart:
	$(MAKE) down
	$(MAKE) up

# Logs
logs:
	docker compose logs -f

# Notificação Slack/Teams (exemplo)
slack_notify:
	@echo "🔔 Notificação: Deploy de $(APP_ENV) concluído!"
	# curl -X POST -H 'Content-type: application/json' --data '{"text":"Deploy de $(APP_ENV) concluído!"}' $WEBHOOK_URL

# Build cache Docker
build-cache:
	docker build --cache-from $(STACK_NAME) -t $(STACK_NAME) .
