#!/bin/bash

# =====================================================
# SIP - Shopee Intelligence Platform
# Management Script
# =====================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Project name
PROJECT="sip"

# Print banner
banner() {
    echo -e "${CYAN}"
    echo "╔═══════════════════════════════════════════════════════╗"
    echo "║     🛒 Shopee Intelligence Platform (SIP)              ║"
    echo "║     Management Script v1.0                            ║"
    echo "╚═══════════════════════════════════════════════════════╝"
    echo -e "${NC}"
}

# Print colored message
info() { echo -e "${BLUE}[INFO]${NC} $1"; }
success() { echo -e "${GREEN}[SUCCESS]${NC} $1"; }
warn() { echo -e "${YELLOW}[WARN]${NC} $1"; }
error() { echo -e "${RED}[ERROR]${NC} $1"; }

# Check if docker is running
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        error "Docker is not running. Please start Docker first."
        exit 1
    fi
}

# =====================================================
# COMMANDS
# =====================================================

# Install - First time setup
cmd_install() {
    banner
    info "Starting first-time installation..."
    
    check_docker
    
    # Copy env file if not exists
    if [ ! -f .env ]; then
        info "Creating .env file..."
        cp .env.example .env
        success ".env file created"
    fi
    
    # Build and start containers
    info "Building Docker containers (this may take a few minutes)..."
    docker-compose build
    
    info "Starting containers..."
    docker-compose up -d
    
    # Wait for database to be ready
    info "Waiting for database to be ready..."
    sleep 10
    
    # Install PHP dependencies
    info "Installing PHP dependencies..."
    docker-compose exec -T app composer install
    
    # Generate app key
    info "Generating application key..."
    docker-compose exec -T app php artisan key:generate
    
    # Run migrations
    info "Running database migrations..."
    docker-compose exec -T app php artisan migrate --seed
    
    # Install NPM dependencies
    info "Installing NPM dependencies..."
    docker-compose exec -T app npm install
    
    # Build assets
    info "Building frontend assets..."
    docker-compose exec -T app npm run build
    
    # Set permissions
    info "Setting permissions..."
    docker-compose exec -T app chmod -R 775 storage bootstrap/cache
    
    success "Installation complete!"
    echo ""
    echo -e "${GREEN}🎉 SIP is now running at: ${CYAN}http://localhost${NC}"
    echo ""
    echo "Default credentials (if seeded):"
    echo "  Email: admin@sip.test"
    echo "  Password: password"
    echo ""
    echo "Useful commands:"
    echo "  ./sip start     - Start all services"
    echo "  ./sip stop      - Stop all services"
    echo "  ./sip logs      - View logs"
    echo "  ./sip shell     - Open shell in app container"
}

# Start services
cmd_start() {
    banner
    check_docker
    info "Starting SIP services..."
    docker-compose up -d
    success "All services started!"
    echo ""
    echo -e "🌐 Web: ${CYAN}http://localhost${NC}"
    echo -e "📧 Mailhog: ${CYAN}http://localhost:8025${NC}"
    echo -e "🔍 Meilisearch: ${CYAN}http://localhost:7700${NC}"
}

# Stop services
cmd_stop() {
    banner
    info "Stopping SIP services..."
    docker-compose down
    success "All services stopped!"
}

# Restart services
cmd_restart() {
    banner
    info "Restarting SIP services..."
    docker-compose restart
    success "All services restarted!"
}

# View logs
cmd_logs() {
    local service=${1:-""}
    if [ -n "$service" ]; then
        docker-compose logs -f "$service"
    else
        docker-compose logs -f
    fi
}

# Shell into container
cmd_shell() {
    local service=${1:-"app"}
    info "Opening shell in $service container..."
    docker-compose exec "$service" /bin/bash
}

# Run artisan command
cmd_artisan() {
    docker-compose exec app php artisan "$@"
}

# Run npm command
cmd_npm() {
    docker-compose exec app npm "$@"
}

# Run composer command
cmd_composer() {
    docker-compose exec app composer "$@"
}

# Database commands
cmd_db() {
    case ${1:-""} in
        migrate)
            info "Running migrations..."
            docker-compose exec app php artisan migrate
            success "Migrations complete!"
            ;;
        fresh)
            warn "This will delete all data! Are you sure? (y/N)"
            read -r confirm
            if [ "$confirm" = "y" ] || [ "$confirm" = "Y" ]; then
                info "Refreshing database..."
                docker-compose exec app php artisan migrate:fresh --seed
                success "Database refreshed!"
            fi
            ;;
        seed)
            info "Running seeders..."
            docker-compose exec app php artisan db:seed
            success "Seeding complete!"
            ;;
        backup)
            local filename="backup_$(date +%Y%m%d_%H%M%S).sql"
            info "Creating backup: $filename"
            docker-compose exec db pg_dump -U sip shopee_intel > "backups/$filename"
            success "Backup created: backups/$filename"
            ;;
        *)
            echo "Database commands:"
            echo "  ./sip db migrate  - Run migrations"
            echo "  ./sip db fresh    - Fresh migrate with seed"
            echo "  ./sip db seed     - Run seeders"
            echo "  ./sip db backup   - Backup database"
            ;;
    esac
}

# Build assets
cmd_build() {
    info "Building frontend assets..."
    docker-compose exec app npm run build
    success "Build complete!"
}

# Development mode with hot reload
cmd_dev() {
    banner
    info "Starting development mode with hot reload..."
    echo ""
    echo "Press Ctrl+C to stop"
    echo ""
    docker-compose exec app npm run dev
}

# Run queue worker
cmd_queue() {
    info "Starting queue worker..."
    docker-compose exec app php artisan horizon
}

# Clear all caches
cmd_clear() {
    info "Clearing all caches..."
    docker-compose exec app php artisan cache:clear
    docker-compose exec app php artisan config:clear
    docker-compose exec app php artisan view:clear
    docker-compose exec app php artisan route:clear
    success "All caches cleared!"
}

# Status of services
cmd_status() {
    banner
    info "Service status:"
    echo ""
    docker-compose ps
}

# Update application
cmd_update() {
    banner
    info "Updating SIP..."
    
    # Pull latest images
    info "Pulling latest images..."
    docker-compose pull
    
    # Rebuild containers
    info "Rebuilding containers..."
    docker-compose build
    
    # Update dependencies
    info "Updating PHP dependencies..."
    docker-compose exec app composer update
    
    info "Updating NPM dependencies..."
    docker-compose exec app npm update
    
    # Run migrations
    info "Running migrations..."
    docker-compose exec app php artisan migrate
    
    # Rebuild assets
    info "Rebuilding assets..."
    docker-compose exec app npm run build
    
    # Restart services
    info "Restarting services..."
    docker-compose restart
    
    success "Update complete!"
}

# Destroy everything
cmd_destroy() {
    banner
    warn "⚠️  This will delete ALL data including database!"
    warn "Are you sure? Type 'yes' to confirm:"
    read -r confirm
    if [ "$confirm" = "yes" ]; then
        info "Destroying all containers and volumes..."
        docker-compose down -v --remove-orphans
        success "Everything destroyed!"
    else
        info "Cancelled."
    fi
}

# Test the application
cmd_test() {
    info "Running tests..."
    docker-compose exec app php artisan test
}

# Show help
cmd_help() {
    banner
    echo "Usage: ./sip <command> [options]"
    echo ""
    echo "Commands:"
    echo "  ${GREEN}install${NC}     First-time installation"
    echo "  ${GREEN}start${NC}       Start all services"
    echo "  ${GREEN}stop${NC}        Stop all services"
    echo "  ${GREEN}restart${NC}     Restart all services"
    echo "  ${GREEN}status${NC}      Show service status"
    echo ""
    echo "  ${GREEN}dev${NC}         Start development mode (hot reload)"
    echo "  ${GREEN}build${NC}       Build frontend assets"
    echo "  ${GREEN}queue${NC}       Run queue worker (Horizon)"
    echo ""
    echo "  ${GREEN}logs${NC} [svc]  View logs (optional: service name)"
    echo "  ${GREEN}shell${NC} [svc] Open shell (default: app)"
    echo ""
    echo "  ${GREEN}artisan${NC}     Run artisan command"
    echo "  ${GREEN}npm${NC}         Run npm command"
    echo "  ${GREEN}composer${NC}    Run composer command"
    echo ""
    echo "  ${GREEN}db${NC}          Database commands (migrate/fresh/seed/backup)"
    echo "  ${GREEN}clear${NC}       Clear all caches"
    echo "  ${GREEN}test${NC}        Run tests"
    echo ""
    echo "  ${GREEN}update${NC}      Update application"
    echo "  ${GREEN}destroy${NC}     Destroy all containers and data"
    echo ""
    echo "Examples:"
    echo "  ./sip install"
    echo "  ./sip logs app"
    echo "  ./sip artisan make:model Product"
    echo "  ./sip db migrate"
}

# =====================================================
# MAIN
# =====================================================

# Create backups directory if not exists
mkdir -p backups

# Parse command
case ${1:-"help"} in
    install)    cmd_install ;;
    start)      cmd_start ;;
    stop)       cmd_stop ;;
    restart)    cmd_restart ;;
    status)     cmd_status ;;
    logs)       cmd_logs "$2" ;;
    shell)      cmd_shell "$2" ;;
    artisan)    shift; cmd_artisan "$@" ;;
    npm)        shift; cmd_npm "$@" ;;
    composer)   shift; cmd_composer "$@" ;;
    db)         shift; cmd_db "$@" ;;
    dev)        cmd_dev ;;
    build)      cmd_build ;;
    queue)      cmd_queue ;;
    clear)      cmd_clear ;;
    test)       cmd_test ;;
    update)     cmd_update ;;
    destroy)    cmd_destroy ;;
    help|--help|-h) cmd_help ;;
    *)
        error "Unknown command: $1"
        echo "Run './sip help' for usage"
        exit 1
        ;;
esac
