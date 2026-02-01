<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopee_id')->unique();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreignId('seller_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('name', 500);
            $table->string('slug', 500)->nullable();
            $table->text('description')->nullable();
            
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('original_price', 15, 2)->nullable();
            $table->integer('discount_percent')->nullable();
            
            $table->integer('stock')->nullable();
            $table->integer('sold_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->integer('liked_count')->default(0);
            
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('rating_count')->default(0);
            
            $table->string('image_url', 500)->nullable();
            $table->json('images')->nullable();
            
            $table->string('brand')->nullable();
            $table->string('condition', 20)->nullable();
            $table->string('location')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            // Computed fields
            $table->decimal('sales_velocity', 10, 2)->nullable();
            $table->string('price_trend', 10)->nullable();
            
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_scraped_at')->nullable();
            $table->timestamps();
            
            $table->index('shopee_id');
            $table->index('category_id');
            $table->index('seller_id');
            $table->index('sold_count');
            $table->index('price');
            $table->index('rating');
            $table->index('sales_velocity');
        });

        // Product Prices History
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->decimal('price', 15, 2);
            $table->decimal('original_price', 15, 2)->nullable();
            $table->integer('discount_percent')->nullable();
            
            $table->integer('stock')->nullable();
            $table->integer('sold_count')->nullable();
            
            $table->timestamp('recorded_at')->useCurrent();
            
            $table->index(['product_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
        Schema::dropIfExists('products');
    }
};
