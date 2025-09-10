# Makefile Laravel Docker
# Suporta: dev, staging, prod

# Comando padrão: dev
APP_ENV ?= dev

# Nome do stack docker
STACK_NAME=laravel_app

# Função para deploy com rollback
define deploy
	@echo "🔹 Deploy iniciado para $$APP_ENV..."
	@docker compose up -d --build || { \
		echo "❌ Deploy falhou, iniciando rollback..."; \
		docker compose down; \
		exit 1; \
	}
	@echo "✅ Deploy concluído!"
endef

# Deploy
up:
	$(call deploy)

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

# Notificação Slack (exemplo simples)
slack_notify:
	@echo "🔔 Notificação: Deploy de $(APP_ENV) concluído!" 
	# Aqui pode colocar curl para webhook do Slack/Teams

# Atualizar cache Docker para acelerar builds
build-cache:
	docker build --cache-from $(STACK_NAME) -t $(STACK_NAME) .
