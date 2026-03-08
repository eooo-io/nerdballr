###############################################################################
# FFIS — Makefile
# Common developer tasks. Run `make help` to list all targets.
###############################################################################

COMPOSE  = docker compose
BACKEND  = $(COMPOSE) exec backend
FRONTEND = $(COMPOSE) exec frontend
ARTISAN  = $(BACKEND) php artisan

.PHONY: help up down restart build logs \
        install migrate fresh seed \
        tinker horizon-status \
        fe-install fe-build \
        minio-ls shell-backend shell-frontend

# ─── Help ─────────────────────────────────────────────────────────────────

help:
	@echo ""
	@echo "  FFIS Dev Commands"
	@echo "  ─────────────────────────────────────────"
	@echo "  make up             Start all services"
	@echo "  make down           Stop all services"
	@echo "  make restart        Restart all services"
	@echo "  make build          Rebuild all images"
	@echo "  make logs           Follow all logs"
	@echo "  make logs s=backend Follow one service log"
	@echo ""
	@echo "  make install        Composer install + migrate + seed"
	@echo "  make migrate        Run migrations"
	@echo "  make fresh          Fresh migrate + seed (destructive)"
	@echo "  make seed           Run database seeders"
	@echo "  make tinker         Open Laravel Tinker"
	@echo "  make horizon-status Show Horizon queue status"
	@echo ""
	@echo "  make fe-install     npm install in frontend"
	@echo "  make fe-build       npm run build in frontend"
	@echo ""
	@echo "  make minio-ls       List MinIO bucket contents"
	@echo "  make shell-backend  Shell into backend container"
	@echo "  make shell-frontend Shell into frontend container"
	@echo ""

# ─── Docker lifecycle ─────────────────────────────────────────────────────

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

restart:
	$(COMPOSE) restart

build:
	$(COMPOSE) build --no-cache

logs:
ifdef s
	$(COMPOSE) logs -f $(s)
else
	$(COMPOSE) logs -f
endif

# ─── Laravel backend ──────────────────────────────────────────────────────

install:
	$(BACKEND) composer install
	$(ARTISAN) key:generate
	$(ARTISAN) migrate --seed
	$(ARTISAN) storage:link

migrate:
	$(ARTISAN) migrate

fresh:
	$(ARTISAN) migrate:fresh --seed

seed:
	$(ARTISAN) db:seed

tinker:
	$(ARTISAN) tinker

horizon-status:
	$(ARTISAN) horizon:status

# ─── Frontend ─────────────────────────────────────────────────────────────

fe-install:
	$(FRONTEND) npm install

fe-build:
	$(FRONTEND) npm run build

# ─── MinIO ────────────────────────────────────────────────────────────────

minio-ls:
	$(COMPOSE) exec minio mc ls local/ffis-media

# ─── Shells ───────────────────────────────────────────────────────────────

shell-backend:
	$(BACKEND) bash

shell-frontend:
	$(FRONTEND) sh
