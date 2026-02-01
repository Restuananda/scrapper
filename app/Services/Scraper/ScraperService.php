<?php

namespace App\Services\Scraper;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Category;
use App\Jobs\ScrapeProductJob;
use App\Jobs\ScrapeSearchJob;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class ScraperService
{
    /**
     * Queue a search scrape job
     */
    public function queueSearch(string $keyword, array $filters = []): void
    {
        $jobData = [
            'type' => 'search',
            'keyword' => $keyword,
            'filters' => $filters,
        ];

        // Push to Redis queue for Node.js scraper
        Redis::rpush('scrape-jobs:search', json_encode($jobData));

        Log::info('Queued search scrape', $jobData);
    }

    /**
     * Queue a product detail scrape job
     */
    public function queueProduct(string $shopeeId): void
    {
        $jobData = [
            'type' => 'product',
            'shopee_id' => $shopeeId,
        ];

        Redis::rpush('scrape-jobs:product', json_encode($jobData));

        Log::info('Queued product scrape', $jobData);
    }

    /**
     * Queue a seller scrape job
     */
    public function queueSeller(string $shopeeId): void
    {
        $jobData = [
            'type' => 'seller',
            'shopee_id' => $shopeeId,
        ];

        Redis::rpush('scrape-jobs:seller', json_encode($jobData));

        Log::info('Queued seller scrape', $jobData);
    }

    /**
     * Process scraped search results (called from webhook/callback)
     */
    public function processSearchResults(array $results): int
    {
        $processed = 0;

        foreach ($results as $item) {
            try {
                $this->upsertProduct($item);
                $processed++;
            } catch (\Exception $e) {
                Log::error('Failed to process scraped product', [
                    'error' => $e->getMessage(),
                    'item' => $item,
                ]);
            }
        }

        return $processed;
    }

    /**
     * Create or update a product from scraped data
     */
    public function upsertProduct(array $data): Product
    {
        $product = Product::updateOrCreate(
            ['shopee_id' => $data['shopee_id']],
            [
                'shop_id' => $data['shop_id'] ?? null,
                'name' => $data['name'],
                'price' => $data['price'],
                'original_price' => $data['original_price'] ?? null,
                'discount_percent' => $data['discount_percent'] ?? null,
                'sold_count' => $data['sold_count'] ?? 0,
                'rating' => $data['rating'] ?? null,
                'rating_count' => $data['rating_count'] ?? 0,
                'location' => $data['location'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'images' => $data['images'] ?? null,
                'first_seen_at' => Product::where('shopee_id', $data['shopee_id'])->value('first_seen_at') ?? now(),
                'last_scraped_at' => now(),
            ]
        );

        // Update sales velocity
        $product->updateSalesVelocity();

        // Record price history
        $product->recordPrice();

        // Handle seller if provided
        if (!empty($data['seller'])) {
            $seller = $this->upsertSeller($data['seller']);
            $product->update(['seller_id' => $seller->id]);
        }

        return $product;
    }

    /**
     * Create or update a seller from scraped data
     */
    public function upsertSeller(array $data): Seller
    {
        return Seller::updateOrCreate(
            ['shopee_id' => $data['shopee_id']],
            [
                'username' => $data['username'] ?? null,
                'name' => $data['name'],
                'avatar' => $data['avatar'] ?? null,
                'rating' => $data['rating'] ?? null,
                'rating_count' => $data['rating_count'] ?? 0,
                'response_rate' => $data['response_rate'] ?? null,
                'follower_count' => $data['follower_count'] ?? 0,
                'product_count' => $data['product_count'] ?? 0,
                'location' => $data['location'] ?? null,
                'is_official_shop' => $data['is_official_shop'] ?? false,
                'is_preferred_seller' => $data['is_preferred_seller'] ?? false,
                'last_scraped_at' => now(),
            ]
        );
    }

    /**
     * Sync categories from Shopee
     */
    public function syncCategories(): void
    {
        // This would be called periodically to sync category tree
        // For now, categories can be seeded manually
        
        $jobData = [
            'type' => 'categories',
        ];

        Redis::rpush('scrape-jobs:category', json_encode($jobData));
    }
}
