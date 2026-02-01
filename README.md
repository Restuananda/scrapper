# 🛒 Shopee Intelligence Platform (SIP)

An all-in-one e-commerce intelligence SaaS built with Laravel 12.

## 🚀 Quick Start

```bash
# 1. Clone/extract the project
cd shopee-intel-platform

# 2. Make script executable
chmod +x sip

# 3. Install everything (first time only)
./sip install

# 4. Open http://localhost in your browser
```

That's it! The `./sip install` command handles everything:
- Builds Docker containers
- Installs PHP & NPM dependencies
- Runs database migrations
- Seeds demo data
- Builds frontend assets

### Demo Accounts
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@sip.test | password |
| User | demo@sip.test | password |

## 📋 Available Commands

```bash
./sip install      # First-time installation
./sip start        # Start all services
./sip stop         # Stop all services
./sip restart      # Restart all services
./sip status       # Show container status

./sip dev          # Start with Vite hot reload
./sip logs         # View logs (all services)
./sip logs app     # View specific service logs
./sip shell        # Open shell in app container

./sip db migrate   # Run migrations
./sip db fresh     # Fresh migrate + seed
./sip db seed      # Run seeders

./sip clear        # Clear all caches
./sip build        # Build for production
./sip test         # Run tests

./sip help         # Show all commands
```

## Features

- **Product Research** - Search & discover trending Shopee products
- **Price Monitoring** - Track products, get price change alerts
- **Market Intelligence** - Category analysis, top seller rankings
- **API Service** - RESTful API for developers
- **Reports** - Generate PDF/Excel market reports

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** Inertia.js + Vue 3 + Tailwind CSS 4
- **Database:** PostgreSQL 16
- **Cache/Queue:** Redis
- **Scraper:** Node.js + Puppeteer
- **Search:** Meilisearch
- **Payment:** Midtrans

## Quick Start

### Using Docker (Recommended)

```bash
# Clone the repository
git clone https://github.com/yourusername/shopee-intel-platform.git
cd shopee-intel-platform

# Copy environment file
cp .env.example .env

# Start all services
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install

# Generate app key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate --seed

# Build frontend assets
docker-compose exec app npm run build
```

### Manual Setup

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate --seed

# Start development server
php artisan serve
npm run dev

# In another terminal, start queue worker
php artisan horizon

# In scraper folder, start scraper service
cd scraper
npm install
npm run dev
```

## Documentation

- **Full Specification:** [PROJECT_MASTER.md](PROJECT_MASTER.md)
- **Quick Start:** [QUICK_START.md](QUICK_START.md)
- **API Docs:** Coming soon

## Development Phases

| Phase | Description | Status |
|-------|-------------|--------|
| 1 | Foundation (Auth, UI, Database) | 📋 Planned |
| 2 | Product Research MVP | 📋 Planned |
| 3 | Subscription System | 📋 Planned |
| 4 | Price Monitoring | 📋 Planned |
| 5 | API & Advanced Features | 📋 Planned |

## Contributing

See PROJECT_MASTER.md for development guidelines.

## License

Proprietary - All rights reserved.
