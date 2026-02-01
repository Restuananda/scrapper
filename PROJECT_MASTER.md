# 🛒 SHOPEE INTELLIGENCE PLATFORM (SIP)
## Complete Project Specification & Development Roadmap

> **Version:** 1.0.0  
> **Last Updated:** February 2026  
> **Framework:** Laravel 12  
> **Continuation Prompt Included:** Yes (see Section 10)

---

## TABLE OF CONTENTS

1. [Project Overview](#1-project-overview)
2. [Product Modules](#2-product-modules)
3. [Technical Architecture](#3-technical-architecture)
4. [Database Schema](#4-database-schema)
5. [Development Phases](#5-development-phases)
6. [API Specification](#6-api-specification)
7. [Pricing & Subscription](#7-pricing--subscription)
8. [Security & Legal](#8-security--legal)
9. [Deployment Guide](#9-deployment-guide)
10. [Continuation Prompt](#10-continuation-prompt)

---

## 1. PROJECT OVERVIEW

### 1.1 What is SIP?

Shopee Intelligence Platform (SIP) is an all-in-one e-commerce intelligence tool that helps:
- **Dropshippers** find winning products
- **Sellers** monitor competitor prices
- **Brands** track market performance
- **Developers** access product data via API
- **Analysts** generate market reports

### 1.2 Core Value Proposition

| User Type | Pain Point | SIP Solution |
|-----------|------------|--------------|
| New Seller | "What product should I sell?" | Trending product finder with sales velocity |
| Existing Seller | "Am I priced competitively?" | Real-time competitor price monitoring |
| Brand Owner | "How is my market share?" | Brand performance dashboard |
| Supplier | "Who are the top sellers?" | Seller ranking & contact discovery |
| Developer | "I need product data" | RESTful API with fair pricing |

### 1.3 Revenue Streams

```
┌─────────────────────────────────────────────────────────────┐
│                    REVENUE MODEL                            │
├─────────────────────────────────────────────────────────────┤
│  1. SUBSCRIPTIONS (Recurring)                               │
│     ├── Starter    : Rp 99k/month   → Product Research      │
│     ├── Pro        : Rp 299k/month  → + Price Monitoring    │
│     ├── Business   : Rp 799k/month  → + Market Intelligence │
│     └── Enterprise : Custom         → Full Access + Support │
│                                                             │
│  2. API ACCESS (Usage-based)                                │
│     ├── Pay-per-request : Rp 500/request                    │
│     └── Monthly quota   : Rp 500k/10k requests              │
│                                                             │
│  3. REPORTS (One-time)                                      │
│     ├── Category Report    : Rp 500k                        │
│     ├── Competitor Analysis: Rp 1jt                         │
│     └── Custom Research    : Rp 2jt+                        │
│                                                             │
│  4. LEADS DATA (One-time)                                   │
│     └── Top Seller List    : Rp 300k - 1jt per category     │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. PRODUCT MODULES

### 2.1 Module Architecture

```
SHOPEE INTELLIGENCE PLATFORM
│
├── 🔍 MODULE A: Product Research (Core)
│   ├── A1: Keyword Search
│   ├── A2: Category Browser
│   ├── A3: Trending Products
│   ├── A4: Product Details
│   └── A5: Bookmarks/Collections
│
├── 📊 MODULE B: Price Monitoring
│   ├── B1: Track Products
│   ├── B2: Track Competitors (Sellers)
│   ├── B3: Price History Charts
│   ├── B4: Price Alerts (Email/WA)
│   └── B5: Price Comparison
│
├── 📈 MODULE C: Market Intelligence
│   ├── C1: Category Analysis
│   ├── C2: Top Sellers Ranking
│   ├── C3: Brand Performance
│   ├── C4: Market Size Estimation
│   └── C5: Trend Forecasting
│
├── 🔌 MODULE D: API Service
│   ├── D1: Product Search API
│   ├── D2: Product Details API
│   ├── D3: Seller Info API
│   ├── D4: Webhook Notifications
│   └── D5: Bulk Data Export
│
├── 📑 MODULE E: Reports & Leads
│   ├── E1: Report Generator
│   ├── E2: Seller Leads Export
│   ├── E3: Custom Report Builder
│   └── E4: Scheduled Reports
│
└── ⚙️ MODULE F: Platform Core
    ├── F1: User Authentication
    ├── F2: Subscription & Billing
    ├── F3: Credit/Quota System
    ├── F4: Admin Dashboard
    └── F5: Scraper Engine
```

### 2.2 Module Details

#### MODULE A: Product Research

**A1: Keyword Search**
```
Input:  keyword, category (optional), price_min, price_max, sort_by
Output: List of products with: name, price, sold, rating, seller, image, link
Limit:  Based on subscription (50/100/500 per search)
```

**A2: Category Browser**
```
Display: Shopee category tree
Action:  Click category → Show top products in category
Data:    Category ID, name, parent, product_count
```

**A3: Trending Products**
```
Algorithm: Products with highest (sold_count / days_listed) ratio
Filters:   Category, price range, minimum sold
Output:    Trending score, sales velocity, growth %
```

**A4: Product Details**
```
Input:  Shopee product URL or product_id
Output: Full product data including:
        - All variants & prices
        - Historical price (if tracked)
        - Seller info & ratings
        - Similar products
```

**A5: Bookmarks/Collections**
```
Features: Save products to collections
          Organize by folders
          Share collections (public link)
          Export collection to CSV
```

#### MODULE B: Price Monitoring

**B1: Track Products**
```
Action:  Add product URL to tracking list
Data:    Check price every 6/12/24 hours (based on plan)
Storage: Price history for 30/90/365 days
```

**B2: Track Competitors**
```
Action:  Add seller/shop URL
Data:    Monitor all products from seller
Alert:   New product added, price changed, product removed
```

**B3: Price History Charts**
```
Display: Line chart with price over time
Features: Compare multiple products
          Mark events (promo, flash sale)
          Export chart as image
```

**B4: Price Alerts**
```
Triggers: Price drops below X
          Price drops by X%
          Competitor changes price
          Product goes out of stock
Channels: Email, WhatsApp, Webhook
```

**B5: Price Comparison**
```
Input:   Multiple product URLs
Output:  Side-by-side comparison table
         Highlight cheapest, best rated, most sold
```

#### MODULE C: Market Intelligence

**C1: Category Analysis**
```
Metrics: Total products, total sellers
         Price distribution (min, max, avg, median)
         Top brands in category
         Sales volume estimation
```

**C2: Top Sellers Ranking**
```
Data:    Seller name, total products, total sales
         Average rating, response rate
         Main categories, location
Filter:  By category, by location, by rating
```

**C3: Brand Performance**
```
Track:   Specific brand across all sellers
Metrics: Total SKUs, price range
         Market share estimation
         Top selling products
```

**C4: Market Size Estimation**
```
Formula: Σ(product_price × estimated_monthly_sales)
Display: By category, by brand, by price range
Compare: Month over month, year over year
```

**C5: Trend Forecasting**
```
Algorithm: Time series analysis on sales data
Output:    Predicted demand next 30/60/90 days
           Seasonal patterns
           Category growth rate
```

#### MODULE D: API Service

**D1: Product Search API**
```http
GET /api/v1/products/search
Parameters:
  - q (keyword)
  - category_id
  - price_min, price_max
  - sort_by (sales, price, rating)
  - page, limit

Response:
{
  "data": [...products],
  "meta": { "total", "page", "limit" },
  "credits_used": 1
}
```

**D2: Product Details API**
```http
GET /api/v1/products/{product_id}
Response:
{
  "data": {
    "id", "name", "price", "original_price",
    "discount", "sold", "rating", "stock",
    "variants": [...],
    "seller": {...},
    "images": [...]
  },
  "credits_used": 1
}
```

**D3: Seller Info API**
```http
GET /api/v1/sellers/{seller_id}
Response:
{
  "data": {
    "id", "name", "rating", "response_rate",
    "products_count", "followers",
    "location", "joined_date",
    "top_products": [...]
  },
  "credits_used": 2
}
```

**D4: Webhook Notifications**
```http
POST /api/v1/webhooks
Body:
{
  "url": "https://your-server.com/webhook",
  "events": ["price_change", "stock_change"],
  "product_ids": [...]
}
```

**D5: Bulk Data Export**
```http
POST /api/v1/export
Body:
{
  "type": "category_products",
  "category_id": "...",
  "format": "csv" | "json",
  "limit": 10000
}

Response:
{
  "job_id": "...",
  "status": "processing",
  "download_url": null // available when complete
}
```

#### MODULE E: Reports & Leads

**E1: Report Generator**
```
Templates:
  - Category Overview Report
  - Competitor Analysis Report
  - Brand Performance Report
  - Monthly Market Summary

Output: PDF with charts and tables
```

**E2: Seller Leads Export**
```
Data:    Seller name, location, product count
         Estimated revenue, main categories
         Contact hints (if available)
Format:  CSV, Excel
```

**E3: Custom Report Builder**
```
Interface: Drag-and-drop report builder
Components: Tables, charts, text, images
Data:      Select from available datasets
```

**E4: Scheduled Reports**
```
Frequency: Daily, weekly, monthly
Delivery:  Email, download link
Format:    PDF, Excel
```

#### MODULE F: Platform Core

**F1: User Authentication**
```
Methods:  Email/password, Google OAuth
Features: Email verification, password reset
          2FA (optional), session management
```

**F2: Subscription & Billing**
```
Gateway:  Midtrans, Xendit
Features: Auto-renewal, invoice generation
          Plan upgrade/downgrade
          Refund handling
```

**F3: Credit/Quota System**
```
Types:
  - Search credits (for product search)
  - Track slots (for price monitoring)
  - API calls (for developers)
  - Export quota (for data export)

Renewal: Monthly based on plan
Overage: Pay-per-use or upgrade prompt
```

**F4: Admin Dashboard**
```
Metrics:  Total users, active subscriptions
          Revenue (MRR, ARR)
          API usage, scraper status
Actions:  User management, plan management
          Manual credit adjustment
          System configuration
```

**F5: Scraper Engine**
```
Architecture: Queue-based job processing
Workers:      Multiple scraper instances
Proxy:        Rotating residential proxies
Rate limit:   Respect Shopee's limits
Storage:      Cache results for efficiency
```

---

## 3. TECHNICAL ARCHITECTURE

### 3.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                         CLIENTS                                     │
├─────────────────────────────────────────────────────────────────────┤
│  Web App (SPA)  │  Mobile App  │  API Clients  │  Browser Extension │
└────────┬────────┴──────┬───────┴───────┬───────┴─────────┬──────────┘
         │               │               │                 │
         └───────────────┴───────┬───────┴─────────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │      LOAD BALANCER      │
                    │        (Nginx)          │
                    └────────────┬────────────┘
                                 │
         ┌───────────────────────┼───────────────────────┐
         │                       │                       │
┌────────▼────────┐   ┌─────────▼─────────┐   ┌────────▼────────┐
│   WEB SERVER    │   │    API SERVER     │   │  WEBHOOK SERVER │
│   (Laravel)     │   │    (Laravel)      │   │   (Laravel)     │
│                 │   │                   │   │                 │
│ - Web routes    │   │ - /api/v1/*       │   │ - Event dispatch│
│ - Blade/Inertia │   │ - Rate limiting   │   │ - Retry logic   │
│ - Session auth  │   │ - API key auth    │   │                 │
└────────┬────────┘   └─────────┬─────────┘   └────────┬────────┘
         │                      │                      │
         └──────────────────────┼──────────────────────┘
                                │
              ┌─────────────────┼─────────────────┐
              │                 │                 │
     ┌────────▼────────┐ ┌─────▼─────┐ ┌────────▼────────┐
     │    DATABASE     │ │   CACHE   │ │     QUEUE       │
     │   (PostgreSQL)  │ │  (Redis)  │ │    (Redis)      │
     │                 │ │           │ │                 │
     │ - Users         │ │ - Session │ │ - Scrape jobs   │
     │ - Products      │ │ - API cache│ │ - Email jobs   │
     │ - Subscriptions │ │ - Rate limit│ │ - Export jobs │
     └─────────────────┘ └───────────┘ └────────┬────────┘
                                                │
                              ┌─────────────────┼─────────────────┐
                              │                 │                 │
                     ┌────────▼────────┐ ┌─────▼─────┐ ┌────────▼────────┐
                     │ SCRAPER WORKER  │ │  WORKER   │ │ SCRAPER WORKER  │
                     │   (Node.js +    │ │ (Laravel) │ │   (Node.js +    │
                     │   Puppeteer)    │ │           │ │   Puppeteer)    │
                     │                 │ │ - Emails  │ │                 │
                     │ - Page scraping │ │ - Exports │ │ - Page scraping │
                     │ - Data extract  │ │ - Reports │ │ - Data extract  │
                     └────────┬────────┘ └───────────┘ └────────┬────────┘
                              │                                 │
                              └─────────────┬───────────────────┘
                                            │
                               ┌────────────▼────────────┐
                               │     PROXY SERVICE      │
                               │  (Rotating Residential) │
                               │                        │
                               │  - IP rotation         │
                               │  - Geo-targeting       │
                               │  - Session management  │
                               └────────────────────────┘
```

### 3.2 Tech Stack

| Layer | Technology | Reason |
|-------|------------|--------|
| **Backend Framework** | Laravel 12 | Modern PHP, excellent ecosystem |
| **Frontend** | Inertia.js + Vue 3 | SPA feel with Laravel backend |
| **CSS** | Tailwind CSS 4 | Utility-first, fast development |
| **Database** | PostgreSQL 16 | Better for analytics, JSON support |
| **Cache** | Redis | Session, cache, queue, rate limiting |
| **Queue** | Laravel Horizon | Job processing with dashboard |
| **Scraper** | Node.js + Puppeteer | Headless browser for JS-heavy sites |
| **Search** | Meilisearch | Fast full-text search for products |
| **Storage** | S3-compatible | Images, exports, reports |
| **Payment** | Midtrans | Indonesian payment gateway |
| **Email** | Mailgun/SES | Transactional emails |
| **WhatsApp** | Fonnte/Wablas | WA notifications |
| **Monitoring** | Laravel Telescope | Debug & monitoring |
| **Deployment** | Docker + Docker Compose | Consistent environments |
| **Server** | Ubuntu 24.04 VPS | DigitalOcean/Vultr/AWS |

### 3.3 Directory Structure

```
shopee-intel-platform/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── ScrapeProducts.php
│   │       ├── ProcessPriceAlerts.php
│   │       └── GenerateReports.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── MonitoringController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   └── SubscriptionController.php
│   │   │   └── Api/
│   │   │       └── V1/
│   │   │           ├── ProductController.php
│   │   │           ├── SellerController.php
│   │   │           ├── ExportController.php
│   │   │           └── WebhookController.php
│   │   ├── Middleware/
│   │   │   ├── CheckSubscription.php
│   │   │   ├── CheckCredits.php
│   │   │   └── ApiRateLimit.php
│   │   └── Requests/
│   │       ├── ProductSearchRequest.php
│   │       └── TrackProductRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Subscription.php
│   │   ├── Plan.php
│   │   ├── Product.php
│   │   ├── ProductPrice.php
│   │   ├── Seller.php
│   │   ├── Category.php
│   │   ├── TrackedProduct.php
│   │   ├── TrackedSeller.php
│   │   ├── PriceAlert.php
│   │   ├── Collection.php
│   │   ├── Report.php
│   │   ├── ApiKey.php
│   │   └── CreditTransaction.php
│   ├── Services/
│   │   ├── Scraper/
│   │   │   ├── ScraperService.php
│   │   │   ├── ProductScraper.php
│   │   │   ├── SellerScraper.php
│   │   │   └── CategoryScraper.php
│   │   ├── Analytics/
│   │   │   ├── TrendingService.php
│   │   │   ├── MarketAnalysisService.php
│   │   │   └── PriceHistoryService.php
│   │   ├── Billing/
│   │   │   ├── SubscriptionService.php
│   │   │   └── CreditService.php
│   │   ├── Notification/
│   │   │   ├── EmailService.php
│   │   │   ├── WhatsAppService.php
│   │   │   └── WebhookService.php
│   │   └── Export/
│   │       ├── CsvExporter.php
│   │       ├── ExcelExporter.php
│   │       └── PdfReportGenerator.php
│   ├── Jobs/
│   │   ├── ScrapeProductJob.php
│   │   ├── ScrapeSellerJob.php
│   │   ├── CheckPriceAlertJob.php
│   │   ├── SendAlertNotificationJob.php
│   │   ├── GenerateReportJob.php
│   │   └── BulkExportJob.php
│   └── Events/
│       ├── PriceChanged.php
│       ├── StockChanged.php
│       └── NewProductFound.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Dashboard.vue
│   │   │   ├── Products/
│   │   │   │   ├── Search.vue
│   │   │   │   ├── Trending.vue
│   │   │   │   └── Detail.vue
│   │   │   ├── Monitoring/
│   │   │   │   ├── TrackedProducts.vue
│   │   │   │   ├── PriceHistory.vue
│   │   │   │   └── Alerts.vue
│   │   │   ├── Intelligence/
│   │   │   │   ├── Categories.vue
│   │   │   │   ├── TopSellers.vue
│   │   │   │   └── BrandAnalysis.vue
│   │   │   ├── Api/
│   │   │   │   ├── Keys.vue
│   │   │   │   ├── Usage.vue
│   │   │   │   └── Docs.vue
│   │   │   └── Settings/
│   │   │       ├── Profile.vue
│   │   │       ├── Subscription.vue
│   │   │       └── Billing.vue
│   │   ├── Components/
│   │   │   ├── ProductCard.vue
│   │   │   ├── PriceChart.vue
│   │   │   ├── DataTable.vue
│   │   │   └── ...
│   │   └── Layouts/
│   │       ├── AppLayout.vue
│   │       └── GuestLayout.vue
│   └── views/
│       └── emails/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
├── scraper/                    # Node.js scraper service
│   ├── package.json
│   ├── src/
│   │   ├── index.js
│   │   ├── scrapers/
│   │   │   ├── product.js
│   │   │   ├── seller.js
│   │   │   └── category.js
│   │   ├── utils/
│   │   │   ├── browser.js
│   │   │   ├── proxy.js
│   │   │   └── parser.js
│   │   └── queue/
│   │       └── consumer.js
│   └── Dockerfile
├── docker/
│   ├── nginx/
│   ├── php/
│   └── node/
├── docker-compose.yml
├── .env.example
└── README.md
```

---

## 4. DATABASE SCHEMA

### 4.1 Core Tables

```sql
-- =====================================================
-- USERS & AUTH
-- =====================================================

CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP,
    password VARCHAR(255),
    google_id VARCHAR(255),
    avatar VARCHAR(255),
    phone VARCHAR(20),
    company VARCHAR(255),
    timezone VARCHAR(50) DEFAULT 'Asia/Jakarta',
    
    -- Credits & Quotas
    search_credits INT DEFAULT 0,
    track_slots INT DEFAULT 0,
    api_calls INT DEFAULT 0,
    export_quota INT DEFAULT 0,
    
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE plans (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,           -- starter, pro, business, enterprise
    display_name VARCHAR(100) NOT NULL,
    price DECIMAL(12,2) NOT NULL,        -- monthly price in IDR
    price_yearly DECIMAL(12,2),          -- yearly price (optional discount)
    
    -- Monthly Quotas
    search_credits INT NOT NULL,
    track_slots INT NOT NULL,
    api_calls INT NOT NULL,
    export_quota INT NOT NULL,
    
    -- Features (JSON for flexibility)
    features JSONB NOT NULL,
    /*
    {
        "price_history_days": 30,
        "check_interval_hours": 24,
        "export_formats": ["csv"],
        "alerts": ["email"],
        "api_access": false,
        "report_templates": ["basic"],
        "support_level": "email"
    }
    */
    
    is_active BOOLEAN DEFAULT true,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscriptions (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    plan_id BIGINT REFERENCES plans(id),
    
    status VARCHAR(20) NOT NULL,         -- active, cancelled, past_due, trialing
    trial_ends_at TIMESTAMP,
    current_period_start TIMESTAMP,
    current_period_end TIMESTAMP,
    cancelled_at TIMESTAMP,
    
    -- Payment gateway info
    gateway VARCHAR(20),                 -- midtrans, xendit
    gateway_subscription_id VARCHAR(255),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE credit_transactions (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    type VARCHAR(20) NOT NULL,           -- search, track, api, export
    amount INT NOT NULL,                 -- positive = add, negative = deduct
    balance_after INT NOT NULL,
    
    description VARCHAR(255),
    reference_type VARCHAR(50),          -- subscription_renewal, manual_topup, usage
    reference_id BIGINT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- PRODUCTS & SELLERS
-- =====================================================

CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    shopee_id BIGINT UNIQUE,
    parent_id BIGINT REFERENCES categories(id),
    
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255),
    level INT DEFAULT 0,
    path VARCHAR(500),                   -- e.g., "1/15/234"
    
    product_count INT DEFAULT 0,
    last_synced_at TIMESTAMP,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sellers (
    id BIGSERIAL PRIMARY KEY,
    shopee_id BIGINT UNIQUE NOT NULL,
    
    username VARCHAR(255),
    name VARCHAR(255) NOT NULL,
    avatar VARCHAR(500),
    
    rating DECIMAL(3,2),
    rating_count INT DEFAULT 0,
    response_rate INT,                   -- percentage
    response_time VARCHAR(50),           -- e.g., "within hours"
    
    follower_count INT DEFAULT 0,
    product_count INT DEFAULT 0,
    
    location VARCHAR(255),
    joined_date DATE,
    is_official_shop BOOLEAN DEFAULT false,
    is_preferred_seller BOOLEAN DEFAULT false,
    
    last_scraped_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    shopee_id BIGINT UNIQUE NOT NULL,
    shop_id BIGINT,                      -- Shopee's shop_id
    seller_id BIGINT REFERENCES sellers(id),
    category_id BIGINT REFERENCES categories(id),
    
    name VARCHAR(500) NOT NULL,
    slug VARCHAR(500),
    description TEXT,
    
    price DECIMAL(15,2),                 -- current price
    original_price DECIMAL(15,2),        -- price before discount
    discount_percent INT,
    
    stock INT,
    sold_count INT DEFAULT 0,
    view_count INT DEFAULT 0,
    liked_count INT DEFAULT 0,
    
    rating DECIMAL(3,2),
    rating_count INT DEFAULT 0,
    
    image_url VARCHAR(500),
    images JSONB,                        -- array of image URLs
    
    brand VARCHAR(255),
    condition VARCHAR(20),               -- new, used
    
    location VARCHAR(255),
    
    is_active BOOLEAN DEFAULT true,
    
    -- Computed fields for analytics
    sales_velocity DECIMAL(10,2),        -- avg sales per day
    price_trend VARCHAR(10),             -- up, down, stable
    
    first_seen_at TIMESTAMP,
    last_scraped_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_seller ON products(seller_id);
CREATE INDEX idx_products_sold ON products(sold_count DESC);
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_products_rating ON products(rating DESC);
CREATE INDEX idx_products_sales_velocity ON products(sales_velocity DESC);

CREATE TABLE product_variants (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT REFERENCES products(id) ON DELETE CASCADE,
    
    name VARCHAR(255),
    price DECIMAL(15,2),
    stock INT,
    sku VARCHAR(100),
    
    tier_index JSONB,                    -- e.g., [0, 1] for variant combination
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_prices (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT REFERENCES products(id) ON DELETE CASCADE,
    
    price DECIMAL(15,2) NOT NULL,
    original_price DECIMAL(15,2),
    discount_percent INT,
    
    stock INT,
    sold_count INT,
    
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_product_prices_product_date ON product_prices(product_id, recorded_at DESC);

-- =====================================================
-- USER TRACKING & MONITORING
-- =====================================================

CREATE TABLE tracked_products (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    product_id BIGINT REFERENCES products(id) ON DELETE CASCADE,
    
    alias VARCHAR(255),                  -- user's custom name
    notes TEXT,
    
    check_interval_hours INT DEFAULT 24,
    last_checked_at TIMESTAMP,
    next_check_at TIMESTAMP,
    
    is_active BOOLEAN DEFAULT true,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(user_id, product_id)
);

CREATE TABLE tracked_sellers (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    seller_id BIGINT REFERENCES sellers(id) ON DELETE CASCADE,
    
    alias VARCHAR(255),
    notes TEXT,
    
    track_new_products BOOLEAN DEFAULT true,
    track_price_changes BOOLEAN DEFAULT true,
    track_stock_changes BOOLEAN DEFAULT false,
    
    last_checked_at TIMESTAMP,
    
    is_active BOOLEAN DEFAULT true,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(user_id, seller_id)
);

CREATE TABLE price_alerts (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    tracked_product_id BIGINT REFERENCES tracked_products(id) ON DELETE CASCADE,
    
    alert_type VARCHAR(20) NOT NULL,     -- price_below, price_drop_percent, any_change
    threshold_value DECIMAL(15,2),       -- price or percentage
    
    notify_email BOOLEAN DEFAULT true,
    notify_whatsapp BOOLEAN DEFAULT false,
    notify_webhook BOOLEAN DEFAULT false,
    webhook_url VARCHAR(500),
    
    is_active BOOLEAN DEFAULT true,
    last_triggered_at TIMESTAMP,
    trigger_count INT DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE alert_logs (
    id BIGSERIAL PRIMARY KEY,
    price_alert_id BIGINT REFERENCES price_alerts(id) ON DELETE CASCADE,
    
    old_price DECIMAL(15,2),
    new_price DECIMAL(15,2),
    
    notification_channels JSONB,         -- ["email", "whatsapp"]
    notification_status JSONB,           -- {"email": "sent", "whatsapp": "failed"}
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- COLLECTIONS & BOOKMARKS
-- =====================================================

CREATE TABLE collections (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    name VARCHAR(255) NOT NULL,
    description TEXT,
    
    is_public BOOLEAN DEFAULT false,
    public_slug VARCHAR(100) UNIQUE,
    
    product_count INT DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE collection_products (
    id BIGSERIAL PRIMARY KEY,
    collection_id BIGINT REFERENCES collections(id) ON DELETE CASCADE,
    product_id BIGINT REFERENCES products(id) ON DELETE CASCADE,
    
    notes TEXT,
    sort_order INT DEFAULT 0,
    
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(collection_id, product_id)
);

-- =====================================================
-- API & WEBHOOKS
-- =====================================================

CREATE TABLE api_keys (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    name VARCHAR(255) NOT NULL,
    key VARCHAR(64) UNIQUE NOT NULL,     -- hashed key
    key_preview VARCHAR(10),             -- first 10 chars for display
    
    permissions JSONB,                   -- ["products:read", "sellers:read"]
    
    rate_limit_per_minute INT DEFAULT 60,
    
    last_used_at TIMESTAMP,
    expires_at TIMESTAMP,
    
    is_active BOOLEAN DEFAULT true,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE api_logs (
    id BIGSERIAL PRIMARY KEY,
    api_key_id BIGINT REFERENCES api_keys(id) ON DELETE SET NULL,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    
    endpoint VARCHAR(255) NOT NULL,
    method VARCHAR(10) NOT NULL,
    
    request_params JSONB,
    response_status INT,
    
    credits_used INT DEFAULT 0,
    response_time_ms INT,
    
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_api_logs_key_date ON api_logs(api_key_id, created_at DESC);
CREATE INDEX idx_api_logs_user_date ON api_logs(user_id, created_at DESC);

CREATE TABLE webhooks (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    url VARCHAR(500) NOT NULL,
    secret VARCHAR(64),                  -- for signature verification
    
    events JSONB NOT NULL,               -- ["price_change", "stock_change"]
    
    is_active BOOLEAN DEFAULT true,
    
    last_triggered_at TIMESTAMP,
    failure_count INT DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE webhook_logs (
    id BIGSERIAL PRIMARY KEY,
    webhook_id BIGINT REFERENCES webhooks(id) ON DELETE CASCADE,
    
    event_type VARCHAR(50) NOT NULL,
    payload JSONB NOT NULL,
    
    response_status INT,
    response_body TEXT,
    response_time_ms INT,
    
    attempt_count INT DEFAULT 1,
    next_retry_at TIMESTAMP,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- REPORTS
-- =====================================================

CREATE TABLE reports (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    type VARCHAR(50) NOT NULL,           -- category_overview, competitor_analysis, etc.
    title VARCHAR(255) NOT NULL,
    
    parameters JSONB NOT NULL,           -- input parameters for report
    
    status VARCHAR(20) DEFAULT 'pending', -- pending, processing, completed, failed
    
    file_path VARCHAR(500),              -- S3 path to generated file
    file_format VARCHAR(10),             -- pdf, xlsx
    file_size INT,
    
    error_message TEXT,
    
    completed_at TIMESTAMP,
    expires_at TIMESTAMP,                -- auto-delete after X days
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE scheduled_reports (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    report_type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    
    parameters JSONB NOT NULL,
    
    frequency VARCHAR(20) NOT NULL,      -- daily, weekly, monthly
    day_of_week INT,                     -- 0-6 for weekly
    day_of_month INT,                    -- 1-31 for monthly
    hour INT DEFAULT 8,                  -- hour to run (0-23)
    
    delivery_method VARCHAR(20),         -- email, download
    delivery_email VARCHAR(255),
    
    is_active BOOLEAN DEFAULT true,
    last_run_at TIMESTAMP,
    next_run_at TIMESTAMP,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- SCRAPER MANAGEMENT
-- =====================================================

CREATE TABLE scrape_jobs (
    id BIGSERIAL PRIMARY KEY,
    
    type VARCHAR(50) NOT NULL,           -- product, seller, category, search
    target_id VARCHAR(255),              -- shopee_id or search query
    
    priority INT DEFAULT 5,              -- 1 = highest, 10 = lowest
    
    status VARCHAR(20) DEFAULT 'pending', -- pending, processing, completed, failed
    
    attempts INT DEFAULT 0,
    max_attempts INT DEFAULT 3,
    
    result JSONB,
    error_message TEXT,
    
    scheduled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    started_at TIMESTAMP,
    completed_at TIMESTAMP,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_scrape_jobs_status ON scrape_jobs(status, priority, scheduled_at);

CREATE TABLE proxy_health (
    id BIGSERIAL PRIMARY KEY,
    
    proxy_url VARCHAR(500) NOT NULL,
    
    success_count INT DEFAULT 0,
    failure_count INT DEFAULT 0,
    
    avg_response_time_ms INT,
    
    last_used_at TIMESTAMP,
    last_success_at TIMESTAMP,
    last_failure_at TIMESTAMP,
    
    is_active BOOLEAN DEFAULT true,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 5. DEVELOPMENT PHASES

### Phase Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    DEVELOPMENT ROADMAP                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  PHASE 1: Foundation (Week 1-2)                                │
│  ├── Laravel setup, auth, basic UI                             │
│  ├── Database migrations                                       │
│  └── Scraper integration                                       │
│                                                                 │
│  PHASE 2: Product Research MVP (Week 3-4)                      │
│  ├── Product search                                            │
│  ├── Category browser                                          │
│  ├── Trending products                                         │
│  └── Bookmarks                                                 │
│                                                                 │
│  PHASE 3: Subscription System (Week 5-6)                       │
│  ├── Plan management                                           │
│  ├── Payment integration                                       │
│  └── Credit system                                             │
│                                                                 │
│  PHASE 4: Price Monitoring (Week 7-8)                          │
│  ├── Track products                                            │
│  ├── Price history                                             │
│  └── Alerts                                                    │
│                                                                 │
│  PHASE 5: API & Advanced (Week 9-12)                           │
│  ├── Public API                                                │
│  ├── Market intelligence                                       │
│  └── Reports                                                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

### PHASE 1: Foundation (Week 1-2)

#### P1.1: Project Setup
```bash
# Create Laravel 12 project
composer create-project laravel/laravel shopee-intel-platform

# Install core packages
composer require inertiajs/inertia-laravel
composer require laravel/sanctum
composer require laravel/horizon
composer require spatie/laravel-permission

# Frontend
npm install @inertiajs/vue3 vue@3 tailwindcss postcss autoprefixer
npm install @headlessui/vue @heroicons/vue
npm install chart.js vue-chartjs
npm install axios lodash dayjs
```

#### P1.2: Authentication
- [ ] User registration with email verification
- [ ] Login with email/password
- [ ] Google OAuth login
- [ ] Password reset
- [ ] Profile management
- [ ] Session management

#### P1.3: Base UI Layout
- [ ] App layout with sidebar navigation
- [ ] Guest layout for auth pages
- [ ] Responsive design
- [ ] Dark mode support (optional)
- [ ] Toast notifications
- [ ] Loading states

#### P1.4: Database Setup
- [ ] Run all migrations
- [ ] Create seeders for:
  - Plans (starter, pro, business, enterprise)
  - Categories (sync from Shopee)
  - Demo user with subscription

#### P1.5: Scraper Integration
- [ ] Set up Node.js scraper service
- [ ] Queue system for scrape jobs
- [ ] Basic proxy rotation
- [ ] Error handling & retries

**Deliverable:** Working auth system with basic dashboard

---

### PHASE 2: Product Research MVP (Week 3-4)

#### P2.1: Product Search
- [ ] Search input with debounce
- [ ] Filters: category, price range, sort
- [ ] Results grid with ProductCard component
- [ ] Pagination
- [ ] Credit deduction per search

#### P2.2: Category Browser
- [ ] Category tree sidebar
- [ ] Click to browse products
- [ ] Category statistics

#### P2.3: Trending Products
- [ ] Algorithm: sales_velocity = sold_count / days_since_first_seen
- [ ] Trending page with filters
- [ ] Time range: 7d, 30d, 90d

#### P2.4: Product Details
- [ ] Full product info page
- [ ] Variants display
- [ ] Seller info
- [ ] Similar products

#### P2.5: Bookmarks/Collections
- [ ] Create collection
- [ ] Add product to collection
- [ ] Manage collections
- [ ] Export collection

**Deliverable:** Functional product research tool

---

### PHASE 3: Subscription System (Week 5-6)

#### P3.1: Plan Management
- [ ] Plans display page
- [ ] Plan comparison table
- [ ] Feature restrictions by plan

#### P3.2: Payment Integration (Midtrans)
- [ ] Midtrans setup
- [ ] Checkout flow
- [ ] Handle payment notifications
- [ ] Invoice generation

#### P3.3: Credit System
- [ ] Credit balance display
- [ ] Credit deduction middleware
- [ ] Credit transaction history
- [ ] Low credit warning

#### P3.4: Subscription Management
- [ ] Current plan display
- [ ] Upgrade/downgrade
- [ ] Cancel subscription
- [ ] Billing history

**Deliverable:** Working subscription & payment system

---

### PHASE 4: Price Monitoring (Week 7-8)

#### P4.1: Track Products
- [ ] Add product to tracking
- [ ] Track list management
- [ ] Check interval settings

#### P4.2: Price History
- [ ] Price recording job
- [ ] Price history chart (Chart.js)
- [ ] Compare multiple products

#### P4.3: Price Alerts
- [ ] Create alert rules
- [ ] Alert evaluation job
- [ ] Email notifications
- [ ] Alert history

#### P4.4: Competitor Tracking
- [ ] Track seller
- [ ] Monitor new products
- [ ] Price change notifications

**Deliverable:** Full price monitoring system

---

### PHASE 5: API & Advanced (Week 9-12)

#### P5.1: Public API
- [ ] API key generation
- [ ] API authentication middleware
- [ ] Rate limiting
- [ ] API documentation (Swagger/OpenAPI)
- [ ] Usage dashboard

#### P5.2: Market Intelligence
- [ ] Category analysis page
- [ ] Top sellers ranking
- [ ] Brand performance (basic)

#### P5.3: Reports
- [ ] Report generation job
- [ ] PDF report template
- [ ] Download reports
- [ ] Scheduled reports (basic)

#### P5.4: Admin Dashboard
- [ ] User management
- [ ] Subscription overview
- [ ] Revenue metrics
- [ ] Scraper status

**Deliverable:** Complete platform

---

## 6. API SPECIFICATION

### 6.1 Authentication

```http
# All API requests require API key in header
Authorization: Bearer {api_key}
```

### 6.2 Endpoints

```yaml
# Product Search
GET /api/v1/products/search
  params:
    q: string (required)
    category_id: int
    price_min: number
    price_max: number
    sort_by: sales|price|rating
    sort_order: asc|desc
    page: int (default: 1)
    limit: int (default: 20, max: 100)
  response:
    data: Product[]
    meta: { total, page, limit, total_pages }
    credits_used: 1

# Product Details
GET /api/v1/products/{shopee_id}
  response:
    data: ProductDetail
    credits_used: 1

# Seller Info
GET /api/v1/sellers/{shopee_id}
  response:
    data: SellerDetail
    credits_used: 2

# Seller Products
GET /api/v1/sellers/{shopee_id}/products
  params:
    page: int
    limit: int
  response:
    data: Product[]
    meta: { total, page, limit }
    credits_used: 2

# Categories
GET /api/v1/categories
  response:
    data: Category[] (tree structure)
    credits_used: 0

# Trending Products
GET /api/v1/products/trending
  params:
    category_id: int
    days: 7|30|90
    limit: int (max: 100)
  response:
    data: TrendingProduct[]
    credits_used: 2

# Bulk Export
POST /api/v1/exports
  body:
    type: category_products|search_results
    params: object
    format: csv|json
  response:
    job_id: string
    status: pending

GET /api/v1/exports/{job_id}
  response:
    status: pending|processing|completed|failed
    download_url: string (when completed)
    credits_used: varies

# Webhooks
POST /api/v1/webhooks
  body:
    url: string
    events: string[]
    product_ids: int[]
  response:
    webhook_id: int
    secret: string

DELETE /api/v1/webhooks/{id}
```

---

## 7. PRICING & SUBSCRIPTION

### 7.1 Plan Matrix

| Feature | Starter | Pro | Business | Enterprise |
|---------|---------|-----|----------|------------|
| **Price/month** | Rp 99k | Rp 299k | Rp 799k | Custom |
| **Search credits** | 500 | 2,000 | 10,000 | Unlimited |
| **Track slots** | 20 | 100 | 500 | Unlimited |
| **API calls** | - | 1,000 | 10,000 | Unlimited |
| **Export quota** | 3 | 20 | 100 | Unlimited |
| **Price history** | 7 days | 30 days | 90 days | 365 days |
| **Check interval** | 24h | 12h | 6h | 1h |
| **Export formats** | CSV | CSV, Excel | All | All |
| **Alerts** | Email | Email, WA | All | All |
| **API access** | ❌ | ✅ | ✅ | ✅ |
| **Reports** | - | Basic | Advanced | Custom |
| **Support** | Email | Email | Priority | Dedicated |

### 7.2 Credit Costs

| Action | Credits |
|--------|---------|
| Product search | 1 |
| Product details | 1 |
| Seller info | 2 |
| Trending products | 2 |
| Category analysis | 5 |
| Bulk export (per 100 items) | 10 |

---

## 8. SECURITY & LEGAL

### 8.1 Security Measures

- [ ] HTTPS everywhere
- [ ] API key hashing (SHA-256)
- [ ] Rate limiting (per IP, per API key)
- [ ] Input validation & sanitization
- [ ] SQL injection prevention (Eloquent ORM)
- [ ] XSS prevention (Vue auto-escaping)
- [ ] CSRF protection
- [ ] Secure session management
- [ ] Password hashing (bcrypt)
- [ ] 2FA (optional, for enterprise)

### 8.2 Legal Considerations

**Disclaimer for users:**
- Data is scraped from public pages
- No guarantee of accuracy
- Users responsible for their use of data
- Comply with Shopee's ToS

**Terms of Service should include:**
- Acceptable use policy
- No reselling raw data
- No automated attacks on Shopee
- Rate limit compliance

---

## 9. DEPLOYMENT GUIDE

### 9.1 Server Requirements

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| CPU | 2 vCPU | 4 vCPU |
| RAM | 4 GB | 8 GB |
| Storage | 50 GB SSD | 100 GB SSD |
| OS | Ubuntu 22.04 | Ubuntu 24.04 |

### 9.2 Docker Compose

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    volumes:
      - .:/var/www/html
    depends_on:
      - db
      - redis

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/conf.d:/etc/nginx/conf.d

  db:
    image: postgres:16
    environment:
      POSTGRES_DB: shopee_intel
      POSTGRES_USER: sip
      POSTGRES_PASSWORD: ${DB_PASSWORD}
    volumes:
      - postgres_data:/var/lib/postgresql/data

  redis:
    image: redis:7-alpine
    volumes:
      - redis_data:/data

  scraper:
    build:
      context: ./scraper
      dockerfile: Dockerfile
    depends_on:
      - redis

  horizon:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    command: php artisan horizon
    depends_on:
      - db
      - redis

  meilisearch:
    image: getmeili/meilisearch:v1.6
    volumes:
      - meilisearch_data:/meili_data

volumes:
  postgres_data:
  redis_data:
  meilisearch_data:
```

---

## 10. CONTINUATION PROMPT

Use this prompt to continue development in future Claude sessions:

---

```
I'm building SHOPEE INTELLIGENCE PLATFORM (SIP) - an e-commerce intelligence SaaS with Laravel 12.

PROJECT CONTEXT:
- Full spec document: [attach PROJECT_MASTER.md]
- Current scraper: [attach shopee-scraper-v3.js]

WHAT SIP DOES:
1. Product Research - Search & discover trending Shopee products
2. Price Monitoring - Track products, get price alerts
3. Market Intelligence - Category analysis, top sellers
4. API Service - RESTful API for developers
5. Reports - Generate PDF/Excel market reports

TECH STACK:
- Backend: Laravel 12
- Frontend: Inertia.js + Vue 3 + Tailwind CSS 4
- Database: PostgreSQL 16
- Cache/Queue: Redis
- Scraper: Node.js + Puppeteer
- Search: Meilisearch
- Payment: Midtrans

CURRENT PHASE: [SPECIFY PHASE NUMBER]
COMPLETED:
- [List what's done]

NEXT TASK:
- [Describe specific task]

IMPORTANT NOTES:
- This is Indonesian market (Shopee.co.id)
- Currency is IDR (Rupiah)
- Use Indonesian language for user-facing text
- Follow Laravel best practices
- Write clean, documented code

Please continue development from where we left off.
```

---

### Quick Resume Prompts

**Resume Phase 1 (Foundation):**
```
Continue SIP development - PHASE 1: Foundation
Need to: Set up Laravel 12 with Inertia/Vue, create auth system, basic layout
Attach: PROJECT_MASTER.md
```

**Resume Phase 2 (Product Research):**
```
Continue SIP development - PHASE 2: Product Research MVP
Need to: Build product search, category browser, trending products
Completed: Auth, basic layout, database migrations
Attach: PROJECT_MASTER.md, current codebase (zip)
```

**Resume Phase 3 (Subscription):**
```
Continue SIP development - PHASE 3: Subscription System
Need to: Implement Midtrans payment, plan management, credit system
Completed: Phase 1-2 (auth, product research)
Attach: PROJECT_MASTER.md, current codebase (zip)
```

**Resume Phase 4 (Price Monitoring):**
```
Continue SIP development - PHASE 4: Price Monitoring
Need to: Track products, price history charts, alerts
Completed: Phase 1-3 (auth, research, subscription)
Attach: PROJECT_MASTER.md, current codebase (zip)
```

**Resume Phase 5 (API & Advanced):**
```
Continue SIP development - PHASE 5: API & Advanced
Need to: Public API, market intelligence, reports
Completed: Phase 1-4 (full MVP)
Attach: PROJECT_MASTER.md, current codebase (zip)
```

---

## APPENDIX A: File Checklist by Phase

### Phase 1 Files
```
[ ] app/Models/User.php (modified)
[ ] app/Models/Plan.php
[ ] app/Models/Subscription.php
[ ] app/Http/Controllers/Auth/*
[ ] resources/js/Layouts/AppLayout.vue
[ ] resources/js/Layouts/GuestLayout.vue
[ ] resources/js/Pages/Auth/Login.vue
[ ] resources/js/Pages/Auth/Register.vue
[ ] resources/js/Pages/Dashboard.vue
[ ] database/migrations/*_create_plans_table.php
[ ] database/migrations/*_create_subscriptions_table.php
[ ] database/seeders/PlanSeeder.php
```

### Phase 2 Files
```
[ ] app/Models/Product.php
[ ] app/Models/Category.php
[ ] app/Models/Seller.php
[ ] app/Models/Collection.php
[ ] app/Services/Scraper/ScraperService.php
[ ] app/Http/Controllers/Web/ProductController.php
[ ] app/Http/Controllers/Web/CategoryController.php
[ ] app/Jobs/ScrapeProductJob.php
[ ] resources/js/Pages/Products/Search.vue
[ ] resources/js/Pages/Products/Trending.vue
[ ] resources/js/Pages/Products/Detail.vue
[ ] resources/js/Pages/Categories/Index.vue
[ ] resources/js/Components/ProductCard.vue
```

### Phase 3 Files
```
[ ] app/Models/CreditTransaction.php
[ ] app/Services/Billing/SubscriptionService.php
[ ] app/Services/Billing/CreditService.php
[ ] app/Http/Controllers/Web/SubscriptionController.php
[ ] app/Http/Controllers/Web/BillingController.php
[ ] app/Http/Middleware/CheckCredits.php
[ ] resources/js/Pages/Subscription/Plans.vue
[ ] resources/js/Pages/Subscription/Checkout.vue
[ ] resources/js/Pages/Settings/Billing.vue
```

### Phase 4 Files
```
[ ] app/Models/TrackedProduct.php
[ ] app/Models/ProductPrice.php
[ ] app/Models/PriceAlert.php
[ ] app/Services/Analytics/PriceHistoryService.php
[ ] app/Http/Controllers/Web/MonitoringController.php
[ ] app/Jobs/CheckPriceAlertJob.php
[ ] resources/js/Pages/Monitoring/TrackedProducts.vue
[ ] resources/js/Pages/Monitoring/PriceHistory.vue
[ ] resources/js/Pages/Monitoring/Alerts.vue
[ ] resources/js/Components/PriceChart.vue
```

### Phase 5 Files
```
[ ] app/Models/ApiKey.php
[ ] app/Models/Report.php
[ ] app/Http/Controllers/Api/V1/*
[ ] app/Http/Middleware/ApiRateLimit.php
[ ] app/Services/Export/PdfReportGenerator.php
[ ] resources/js/Pages/Api/Keys.vue
[ ] resources/js/Pages/Api/Docs.vue
[ ] resources/js/Pages/Intelligence/Categories.vue
[ ] resources/js/Pages/Reports/Index.vue
```

---

**END OF PROJECT MASTER DOCUMENT**
