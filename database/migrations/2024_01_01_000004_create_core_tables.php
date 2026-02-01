<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Credit Transactions
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('type', 20); // search, track, api, export
            $table->integer('amount'); // positive = add, negative = deduct
            $table->integer('balance_after');
            
            $table->string('description')->nullable();
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['user_id', 'created_at']);
        });

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopee_id')->unique()->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('set null');
            
            $table->string('name');
            $table->string('slug')->nullable();
            $table->integer('level')->default(0);
            $table->string('path', 500)->nullable();
            
            $table->integer('product_count')->default(0);
            $table->timestamp('last_synced_at')->nullable();
            
            $table->timestamps();
            
            $table->index('shopee_id');
            $table->index('parent_id');
        });

        // Sellers
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopee_id')->unique();
            
            $table->string('username')->nullable();
            $table->string('name');
            $table->string('avatar', 500)->nullable();
            
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('rating_count')->default(0);
            $table->integer('response_rate')->nullable();
            $table->string('response_time', 50)->nullable();
            
            $table->integer('follower_count')->default(0);
            $table->integer('product_count')->default(0);
            
            $table->string('location')->nullable();
            $table->date('joined_date')->nullable();
            $table->boolean('is_official_shop')->default(false);
            $table->boolean('is_preferred_seller')->default(false);
            
            $table->timestamp('last_scraped_at')->nullable();
            $table->timestamps();
            
            $table->index('shopee_id');
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('credit_transactions');
    }
};
