#!/bin/bash

# =====================================================
# SHOPEE INTELLIGENCE PLATFORM - Easy Setup Script
# =====================================================
# Usage: ./sip.sh [command]
# =====================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Banner
show_banner() {
    echo -e "${CYAN}"
    echo "╔═══════════════════════════════════════════════════════════╗"
    echo "║                                                           ║"
    echo "║   ⚡ SHOPEE INTELLIGENCE PLATFORM                        ║"
    echo "║      E-commerce Analytics SaaS                           ║"
    echo "║                                                           ║"
    echo "╚═══════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
}

# Help
show_help() {
    show_banner
    echo -e "${YELLOW}Usage:${NC} ./sip.sh [command]"
    echo ""
    echo -e "${GREEN}🚀 Quick Start:${NC}"
    echo "   install     - First time setup (install everything)"
    echo "   start       - Start all services"
    echo "   stop        - Stop all services"
    echo "   restart     - Restart all services"
    echo ""
    echo -e "${GREEN}🔧 Development:${NC}"
    echo "   dev         - Start dev mode with Vite HMR"
    echo "   logs        - View all logs"
    echo "   bash        - Open shell in app container"
    echo "   tinker      - Open Laravel Tinker"
    echo ""
    echo -e "${GREEN}🗄️ Database:${NC}"
    echo "   migrate     - Run migrations"
    echo "   fresh       - Fresh migrate + seed"
    echo "   seed        - Run seeders"
    echo ""
    echo -e "${GREEN}🧹 Maintenance:${NC}"
    echo "   clean       - Clear all caches"
    echo "   build       - Build for production"
    echo "   status      - Show container status"
    echo ""
}

# Check Docker
check_docker() {
    if ! command -v docker &> /dev/null; then
        echo -e "${RED}❌ Docker is not installed!${NC}"
        echo "Please install Docker first: https://docs.docker.com/get-docker/"
        exit 1
    fi
    
    if ! docker info &> /dev/null; then
        echo -e "${RED}❌ Docker is not running!${NC}"
        echo "Please start Docker and try again."
        exit 1
    fi
}

# Install
cmd_install() {
    show_banner
    echo -e "${YELLOW}🚀 Installing Shopee Intelligence Platform...${NC}"
    echo ""
    
    check_docker
    
    # Copy env
    if [ ! -f .env ]; then
        echo -e "${BLUE}📝 Creating .env file...${NC}"
        cp .env.example .env
    fi
    
    # Build containers
    echo -e "${BLUE}🐳 Building Docker containers...${NC}"
    docker-compose build
    
    # Start containers
    echo -e "${BLUE}🚀 Starting containers...${NC}"
    docker-compose up -d
    
    # Wait for DB
    echo -e "${BLUE}⏳ Waiting for database to be ready...${NC}"
    sleep 15
    
    # Install PHP dependencies
    echo -e "${BLUE}📦 Installing PHP dependencies...${NC}"
    docker-compose exec -T app composer install --no-interaction
    
    # Generate key
    echo -e "${BLUE}🔑 Generating application key...${NC}"
    docker-compose exec -T app php artisan key:generate
    
    # Run migrations
    echo -e "${BLUE}🗄️ Running database migrations...${NC}"
    docker-compose exec -T app php artisan migrate --seed --force
    
    # Install NPM
    echo -e "${BLUE}📦 Installing NPM dependencies...${NC}"
    docker-compose exec -T app npm install
    
    # Build assets
    echo -e "${BLUE}🔨 Building frontend assets...${NC}"
    docker-compose exec -T app npm run build
    
    echo ""
    echo -e "${GREEN}╔═══════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║  ✅ Installation Complete!                                ║${NC}"
    echo -e "${GREEN}╠═══════════════════════════════════════════════════════════╣${NC}"
    echo -e "${GREEN}║                                                           ║${NC}"
    echo -e "${GREEN}║  🌐 App:        http://localhost                          ║${NC}"
    echo -e "${GREEN}║  📧 Mailhog:    http://localhost:8025                     ║${NC}"
    echo -e "${GREEN}║  🔍 Meilisearch: http://localhost:7700                    ║${NC}"
    echo -e "${GREEN}║                                                           ║${NC}"
    echo -e "${GREEN}║  📝 Demo Account:                                         ║${NC}"
    echo -e "${GREEN}║     Email: demo@sip.test                                  ║${NC}"
    echo -e "${GREEN}║     Pass:  password                                       ║${NC}"
    echo -e "${GREEN}║                                                           ║${NC}"
    echo -e "${GREEN}╚═══════════════════════════════════════════════════════════╝${NC}"
    echo ""
}

# Start
cmd_start() {
    check_docker
    echo -e "${BLUE}🚀 Starting services...${NC}"
    docker-compose up -d
    echo ""
    echo -e "${GREEN}✅ Services started!${NC}"
    echo -e "   🌐 App: ${CYAN}http://localhost${NC}"
    echo ""
}

# Stop
cmd_stop() {
    echo -e "${BLUE}🛑 Stopping services...${NC}"
    docker-compose down
    echo -e "${GREEN}✅ All services stopped${NC}"
}

# Restart
cmd_restart() {
    echo -e "${BLUE}🔄 Restarting services...${NC}"
    docker-compose restart
    echo -e "${GREEN}✅ Services restarted${NC}"
}

# Dev mode
cmd_dev() {
    check_docker
    echo -e "${BLUE}🚀 Starting development mode...${NC}"
    docker-compose up -d
    echo ""
    echo -e "${GREEN}✅ Services started! Starting Vite...${NC}"
    echo -e "${YELLOW}   Press Ctrl+C to stop Vite${NC}"
    echo ""
    docker-compose exec app npm run dev
}

# Logs
cmd_logs() {
    docker-compose logs -f
}

# Bash
cmd_bash() {
    docker-compose exec app bash
}

# Tinker
cmd_tinker() {
    docker-compose exec app php artisan tinker
}

# Migrate
cmd_migrate() {
    echo -e "${BLUE}🗄️ Running migrations...${NC}"
    docker-compose exec app php artisan migrate
    echo -e "${GREEN}✅ Migrations complete${NC}"
}

# Fresh
cmd_fresh() {
    echo -e "${YELLOW}⚠️ This will reset the database. Continue? (y/n)${NC}"
    read -r confirm
    if [ "$confirm" = "y" ]; then
        echo -e "${BLUE}🗄️ Resetting database...${NC}"
        docker-compose exec app php artisan migrate:fresh --seed
        echo -e "${GREEN}✅ Database reset complete${NC}"
    else
        echo "Cancelled."
    fi
}

# Seed
cmd_seed() {
    echo -e "${BLUE}🌱 Running seeders...${NC}"
    docker-compose exec app php artisan db:seed
    echo -e "${GREEN}✅ Seeding complete${NC}"
}

# Clean
cmd_clean() {
    echo -e "${BLUE}🧹 Clearing caches...${NC}"
    docker-compose exec app php artisan cache:clear
    docker-compose exec app php artisan config:clear
    docker-compose exec app php artisan view:clear
    docker-compose exec app php artisan route:clear
    echo -e "${GREEN}✅ All caches cleared${NC}"
}

# Build
cmd_build() {
    echo -e "${BLUE}🔨 Building for production...${NC}"
    docker-compose exec app npm run build
    docker-compose exec app php artisan optimize
    echo -e "${GREEN}✅ Build complete${NC}"
}

# Status
cmd_status() {
    docker-compose ps
}

# Main
case "${1:-}" in
    install)
        cmd_install
        ;;
    start)
        cmd_start
        ;;
    stop)
        cmd_stop
        ;;
    restart)
        cmd_restart
        ;;
    dev)
        cmd_dev
        ;;
    logs)
        cmd_logs
        ;;
    bash)
        cmd_bash
        ;;
    tinker)
        cmd_tinker
        ;;
    migrate)
        cmd_migrate
        ;;
    fresh)
        cmd_fresh
        ;;
    seed)
        cmd_seed
        ;;
    clean)
        cmd_clean
        ;;
    build)
        cmd_build
        ;;
    status)
        cmd_status
        ;;
    *)
        show_help
        ;;
esac
