<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plan;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Plans first
        $this->call(PlanSeeder::class);

        // Create demo admin user
        $admin = User::create([
            'name' => 'Admin SIP',
            'email' => 'admin@sip.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'search_credits' => 10000,
            'track_slots' => 500,
            'api_calls' => 10000,
            'export_quota' => 100,
        ]);

        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@sip.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'search_credits' => 500,
            'track_slots' => 20,
            'api_calls' => 0,
            'export_quota' => 3,
        ]);

        // Assign starter plan to demo user
        $starterPlan = Plan::where('name', 'starter')->first();
        if ($starterPlan) {
            $user->subscriptions()->create([
                'plan_id' => $starterPlan->id,
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
            ]);
        }

        // Assign business plan to admin
        $businessPlan = Plan::where('name', 'business')->first();
        if ($businessPlan) {
            $admin->subscriptions()->create([
                'plan_id' => $businessPlan->id,
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
            ]);
        }

        // Seed sample categories
        $this->seedCategories();

        // Seed sample sellers
        $this->seedSellers();

        // Seed sample products
        $this->seedProducts();

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Demo Accounts:');
        $this->command->info('  Admin: admin@sip.test / password');
        $this->command->info('  User:  demo@sip.test / password');
    }

    protected function seedCategories(): void
    {
        $categories = [
            ['shopee_id' => 1, 'name' => 'Fashion Pria', 'level' => 0],
            ['shopee_id' => 2, 'name' => 'Fashion Wanita', 'level' => 0],
            ['shopee_id' => 3, 'name' => 'Elektronik', 'level' => 0],
            ['shopee_id' => 4, 'name' => 'Handphone & Aksesoris', 'level' => 0],
            ['shopee_id' => 5, 'name' => 'Komputer & Aksesoris', 'level' => 0],
            ['shopee_id' => 6, 'name' => 'Kesehatan', 'level' => 0],
            ['shopee_id' => 7, 'name' => 'Kecantikan', 'level' => 0],
            ['shopee_id' => 8, 'name' => 'Rumah & Kehidupan', 'level' => 0],
            ['shopee_id' => 9, 'name' => 'Makanan & Minuman', 'level' => 0],
            ['shopee_id' => 10, 'name' => 'Olahraga & Outdoor', 'level' => 0],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }

    protected function seedSellers(): void
    {
        $sellers = [
            [
                'shopee_id' => 100001,
                'name' => 'TokoKaos Official',
                'username' => 'tokokaos',
                'rating' => 4.9,
                'rating_count' => 15420,
                'follower_count' => 125000,
                'product_count' => 350,
                'location' => 'Jakarta Barat',
                'is_official_shop' => true,
            ],
            [
                'shopee_id' => 100002,
                'name' => 'Fashion Store ID',
                'username' => 'fashionstore',
                'rating' => 4.8,
                'rating_count' => 8900,
                'follower_count' => 67000,
                'product_count' => 520,
                'location' => 'Bandung',
            ],
            [
                'shopee_id' => 100003,
                'name' => 'Gadget World',
                'username' => 'gadgetworld',
                'rating' => 4.7,
                'rating_count' => 12300,
                'follower_count' => 89000,
                'product_count' => 180,
                'location' => 'Jakarta Selatan',
                'is_preferred_seller' => true,
            ],
        ];

        foreach ($sellers as $seller) {
            Seller::create($seller);
        }
    }

    protected function seedProducts(): void
    {
        $products = [
            [
                'shopee_id' => 1000001,
                'shop_id' => 100001,
                'seller_id' => 1,
                'category_id' => 1,
                'name' => 'Kaos Polos Premium Cotton Combed 30s Unisex - All Size',
                'price' => 45000,
                'original_price' => 75000,
                'discount_percent' => 40,
                'sold_count' => 12500,
                'rating' => 4.9,
                'rating_count' => 2340,
                'location' => 'Jakarta Barat',
                'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400',
                'sales_velocity' => 178.5,
                'first_seen_at' => now()->subDays(70),
            ],
            [
                'shopee_id' => 1000002,
                'shop_id' => 100002,
                'seller_id' => 2,
                'category_id' => 1,
                'name' => 'Celana Jogger Training Pria Wanita Bahan Premium',
                'price' => 89000,
                'original_price' => 150000,
                'discount_percent' => 41,
                'sold_count' => 8700,
                'rating' => 4.8,
                'rating_count' => 1890,
                'location' => 'Bandung',
                'image_url' => 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?w=400',
                'sales_velocity' => 124.2,
                'first_seen_at' => now()->subDays(70),
            ],
            [
                'shopee_id' => 1000003,
                'shop_id' => 100003,
                'seller_id' => 3,
                'category_id' => 4,
                'name' => 'Headphone Bluetooth Wireless Gaming RGB LED',
                'price' => 125000,
                'original_price' => 250000,
                'discount_percent' => 50,
                'sold_count' => 6800,
                'rating' => 4.6,
                'rating_count' => 1560,
                'location' => 'Jakarta Selatan',
                'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400',
                'sales_velocity' => 97.1,
                'first_seen_at' => now()->subDays(70),
            ],
            [
                'shopee_id' => 1000004,
                'shop_id' => 100001,
                'seller_id' => 1,
                'category_id' => 1,
                'name' => 'Tas Selempang Pria Anti Air Waterproof USB Charging',
                'price' => 75000,
                'original_price' => 120000,
                'discount_percent' => 38,
                'sold_count' => 9200,
                'rating' => 4.8,
                'rating_count' => 2100,
                'location' => 'Jakarta Barat',
                'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400',
                'sales_velocity' => 131.4,
                'first_seen_at' => now()->subDays(70),
            ],
            [
                'shopee_id' => 1000005,
                'shop_id' => 100002,
                'seller_id' => 2,
                'category_id' => 1,
                'name' => 'Sepatu Sneakers Casual Import Premium Quality',
                'price' => 259000,
                'original_price' => 450000,
                'discount_percent' => 42,
                'sold_count' => 5400,
                'rating' => 4.7,
                'rating_count' => 980,
                'location' => 'Bandung',
                'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400',
                'sales_velocity' => 77.1,
                'first_seen_at' => now()->subDays(70),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
