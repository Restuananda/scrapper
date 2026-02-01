# 🚀 SIP Quick Start Checklist

## Before You Start

### Prerequisites
- [ ] PHP 8.3+
- [ ] Composer 2.x
- [ ] Node.js 20+
- [ ] PostgreSQL 16
- [ ] Redis 7
- [ ] Docker (optional but recommended)

### Accounts Needed
- [ ] Midtrans account (payment gateway)
- [ ] Mailgun/SES account (emails)
- [ ] Fonnte/Wablas account (WhatsApp - optional)
- [ ] Proxy service (for scraping)

---

## Phase 1 Checklist ✅

### Day 1: Project Setup
```bash
# 1. Create Laravel project
composer create-project laravel/laravel shopee-intel-platform
cd shopee-intel-platform

# 2. Install backend packages
composer require inertiajs/inertia-laravel
composer require laravel/sanctum
composer require laravel/horizon
composer require spatie/laravel-permission
composer require laravel/socialite
composer require barryvdh/laravel-dompdf

# 3. Install frontend
npm install @inertiajs/vue3 vue@3
npm install -D tailwindcss postcss autoprefixer
npm install @headlessui/vue @heroicons/vue
npm install chart.js vue-chartjs axios lodash dayjs
npx tailwindcss init -p

# 4. Configure Inertia
php artisan inertia:middleware
# Add to app/Http/Kernel.php

# 5. Set up database
# Edit .env with PostgreSQL credentials
php artisan migrate
```

### Day 2: Authentication
- [ ] Install Laravel Breeze or build custom auth
- [ ] Create login page (resources/js/Pages/Auth/Login.vue)
- [ ] Create register page (resources/js/Pages/Auth/Register.vue)
- [ ] Email verification
- [ ] Password reset
- [ ] Google OAuth setup

### Day 3: Layout & UI
- [ ] Create AppLayout.vue with sidebar
- [ ] Create GuestLayout.vue for auth pages
- [ ] Set up Tailwind with custom colors
- [ ] Create base components:
  - Button.vue
  - Input.vue
  - Modal.vue
  - Dropdown.vue
  - Toast.vue

### Day 4: Database & Seeders
- [ ] Run migrations from PROJECT_MASTER.md
- [ ] Create PlanSeeder with 4 plans
- [ ] Create CategorySeeder (sync from Shopee)
- [ ] Create demo user

### Day 5-7: Scraper Integration
- [ ] Set up /scraper folder with Node.js
- [ ] Install Puppeteer
- [ ] Create queue consumer for scrape jobs
- [ ] Test basic scraping
- [ ] Set up proxy rotation

---

## Phase 2 Checklist ✅

### Week 2: Product Research

**Day 1-2: Product Search**
- [ ] ProductController.php
- [ ] Search.vue page
- [ ] ProductCard.vue component
- [ ] Search filters (category, price, sort)
- [ ] Pagination

**Day 3: Categories**
- [ ] CategoryController.php
- [ ] Categories/Index.vue
- [ ] Category tree component
- [ ] Category products view

**Day 4-5: Trending**
- [ ] TrendingService.php
- [ ] Trending algorithm implementation
- [ ] Trending.vue page
- [ ] Time range filters

**Day 6-7: Bookmarks**
- [ ] Collection model & controller
- [ ] Collections/Index.vue
- [ ] Add to collection modal
- [ ] Export collection

---

## Phase 3 Checklist ✅

### Week 3: Subscription

**Day 1-2: Plans**
- [ ] Plan model complete
- [ ] Plans.vue comparison page
- [ ] Feature gating middleware

**Day 3-4: Midtrans**
- [ ] Midtrans SDK setup
- [ ] Checkout flow
- [ ] Payment notification handler
- [ ] Invoice model & generation

**Day 5-6: Credits**
- [ ] CreditService.php
- [ ] CheckCredits middleware
- [ ] Credit transaction logging
- [ ] Low credit warning component

**Day 7: Subscription Management**
- [ ] Current plan display
- [ ] Upgrade/downgrade flow
- [ ] Cancellation flow
- [ ] Billing history page

---

## Phase 4 Checklist ✅

### Week 4: Price Monitoring

**Day 1-2: Tracking**
- [ ] TrackedProduct model
- [ ] Add tracking UI
- [ ] Tracked products list
- [ ] Check interval settings

**Day 3-4: Price History**
- [ ] ProductPrice model
- [ ] Price recording job (scheduled)
- [ ] PriceChart.vue component
- [ ] Compare products chart

**Day 5-6: Alerts**
- [ ] PriceAlert model
- [ ] Alert creation UI
- [ ] CheckPriceAlertJob
- [ ] Email notification
- [ ] WhatsApp notification (optional)

**Day 7: Competitor Tracking**
- [ ] TrackedSeller model
- [ ] Track seller UI
- [ ] New product alerts

---

## Phase 5 Checklist ✅

### Week 5-6: API & Advanced

**API (Week 5)**
- [ ] ApiKey model
- [ ] API authentication middleware
- [ ] Rate limiting
- [ ] /api/v1/products/search endpoint
- [ ] /api/v1/products/{id} endpoint
- [ ] /api/v1/sellers/{id} endpoint
- [ ] API documentation page
- [ ] Usage dashboard

**Intelligence & Reports (Week 6)**
- [ ] Category analysis page
- [ ] Top sellers ranking
- [ ] Report generation job
- [ ] PDF report template
- [ ] Scheduled reports

---

## Launch Checklist 🚀

### Before Launch
- [ ] Security audit
- [ ] Performance testing
- [ ] Error monitoring (Sentry)
- [ ] Backup strategy
- [ ] Terms of Service
- [ ] Privacy Policy
- [ ] SSL certificate
- [ ] CDN setup

### Marketing
- [ ] Landing page
- [ ] Demo video
- [ ] Documentation
- [ ] Social media accounts
- [ ] Launch on communities (Kaskus, Facebook groups)

---

## Key Commands Reference

```bash
# Development
php artisan serve
npm run dev
php artisan horizon

# Database
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Queue
php artisan queue:work
php artisan horizon

# Cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Scraper (in /scraper folder)
npm run dev
npm run worker
```

---

## Useful Links

- Laravel Docs: https://laravel.com/docs/12.x
- Inertia.js: https://inertiajs.com
- Vue 3: https://vuejs.org
- Tailwind CSS: https://tailwindcss.com
- Midtrans Docs: https://docs.midtrans.com
- Puppeteer: https://pptr.dev

---

## Support & Questions

When continuing development with Claude, always:
1. Attach PROJECT_MASTER.md
2. Specify current phase
3. List completed items
4. Describe specific task needed

Example prompt:
```
Continue SIP development - PHASE 2, Day 3
Completed: Auth, layout, product search
Need: Category browser with tree view
Attach: PROJECT_MASTER.md, current code
```
