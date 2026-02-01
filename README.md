# 🛒 Shopee Intelligence Platform (SIP)

An all-in-one e-commerce intelligence SaaS built with Laravel 12.

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
