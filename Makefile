# =====================================================
# SHOPEE INTELLIGENCE PLATFORM - Makefile
# =====================================================
# Usage: make [command]
# Run 'make help' to see all available commands
# =====================================================

.PHONY: help install dev build up down restart logs shell db-fresh db-seed test clean

# Default
help:
	@echo ""
	@echo "  ╔═══════════════════════════════════════════════════╗"
	@echo "  ║   SHOPEE INTELLIGENCE PLATFORM - Commands         ║"
	@echo "  ╚═══════════════════════════════════════════════════╝"
	@echo ""
	@echo "  🚀 QUICK START:"
	@echo "     make install    - First time setup (install everything)"
	@echo "     make dev        - Start development environment"
	@echo "     make stop       - Stop all services"
	@echo ""
	@echo "  🐳 DOCKER:"
	@echo "     make up         - Start Docker containers"
	@echo "     make down       - Stop Docker containers"
	@echo "     make restart    - Restart all containers"
	@echo "     make logs       - View container logs"
	@echo "     make ps         - Show running containers"
	@echo ""
	@echo "  🔧 DEVELOPMENT:"
	@echo "     make shell      - Open Laravel shell (tinker)"
	@echo "     make bash       - Open bash in app container"
	@echo "     make npm-dev    - Run npm dev (Vite)"
	@echo "     make horizon    - Start Laravel Horizon"
	@echo ""
	@echo "  🗄️  DATABASE:"
	@echo "     make migrate    - Run migrations"
	@echo "     make db-fresh   - Fresh migrate + seed"
	@echo "     make db-seed    - Run seeders"
	@echo "     make db-reset   - Reset database completely"
	@echo ""
	@echo "  🧹 MAINTENANCE:"
	@echo "     make clean      - Clear all caches"
	@echo "     make test       - Run tests"
	@echo "     make build      - Build for production"
	@echo ""

# =====================================================
# QUICK START
# =====================================================

install:
	@echo "🚀 Installing Shopee Intelligence Platform..."
	@cp -n .env.example .env || true
	@docker-compose build
	@docker-compose up -d
	@echo "⏳ Waiting for containers to be ready..."
	@sleep 10
	@docker-compose exec app composer install
	@docker-compose exec app php artisan key:generate
	@docker-compose exec app php artisan migrate --seed
	@docker-compose exec app npm install
	@docker-compose exec app npm run build
	@echo ""
	@echo "✅ Installation complete!"
	@echo "🌐 Open http://localhost in your browser"
	@echo ""

dev:
	@echo "🚀 Starting development environment..."
	@docker-compose up -d
	@echo ""
	@echo "✅ Services started!"
	@echo "🌐 App: http://localhost"
	@echo "📧 Mailhog: http://localhost:8025"
	@echo "🔍 Meilisearch: http://localhost:7700"
	@echo ""
	@echo "💡 Run 'make npm-dev' in another terminal for Vite HMR"
	@echo ""

stop:
	@echo "🛑 Stopping all services..."
	@docker-compose down
	@echo "✅ All services stopped"

# =====================================================
# DOCKER COMMANDS
# =====================================================

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose restart

logs:
	docker-compose logs -f

logs-app:
	docker-compose logs -f app

logs-scraper:
	docker-compose logs -f scraper

ps:
	docker-compose ps

build:
	docker-compose build

# =====================================================
# DEVELOPMENT COMMANDS
# =====================================================

shell:
	docker-compose exec app php artisan tinker

bash:
	docker-compose exec app bash

npm-dev:
	docker-compose exec app npm run dev

npm-build:
	docker-compose exec app npm run build

horizon:
	docker-compose exec app php artisan horizon

queue:
	docker-compose exec app php artisan queue:work

# =====================================================
# DATABASE COMMANDS
# =====================================================

migrate:
	docker-compose exec app php artisan migrate

db-fresh:
	docker-compose exec app php artisan migrate:fresh --seed

db-seed:
	docker-compose exec app php artisan db:seed

db-reset:
	docker-compose exec app php artisan db:wipe
	docker-compose exec app php artisan migrate --seed

# =====================================================
# MAINTENANCE COMMANDS
# =====================================================

clean:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan view:clear
	docker-compose exec app php artisan route:clear
	@echo "✅ All caches cleared"

test:
	docker-compose exec app php artisan test

optimize:
	docker-compose exec app php artisan optimize
	docker-compose exec app php artisan view:cache
	docker-compose exec app php artisan route:cache

# =====================================================
# SCRAPER COMMANDS
# =====================================================

scraper-logs:
	docker-compose logs -f scraper

scraper-restart:
	docker-compose restart scraper
