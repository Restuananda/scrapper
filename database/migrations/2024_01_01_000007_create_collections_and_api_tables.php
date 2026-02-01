<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Collections
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->text('description')->nullable();
            
            $table->boolean('is_public')->default(false);
            $table->string('public_slug', 100)->unique()->nullable();
            
            $table->integer('product_count')->default(0);
            
            $table->timestamps();
        });

        // Collection Products Pivot
        Schema::create('collection_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            
            $table->timestamp('added_at')->useCurrent();
            
            $table->unique(['collection_id', 'product_id']);
        });

        // API Keys
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->string('key', 64)->unique();
            $table->string('key_preview', 15);
            
            $table->json('permissions')->nullable();
            
            $table->integer('rate_limit_per_minute')->default(60);
            
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });

        // API Logs
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('endpoint');
            $table->string('method', 10);
            $table->integer('status_code');
            $table->integer('response_time_ms');
            
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['api_key_id', 'created_at']);
        });

        // Scrape Jobs
        Schema::create('scrape_jobs', function (Blueprint $table) {
            $table->id();
            
            $table->string('type', 50);
            $table->string('target_id')->nullable();
            
            $table->integer('priority')->default(5);
            
            $table->string('status', 20)->default('pending');
            
            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(3);
            
            $table->json('result')->nullable();
            $table->text('error_message')->nullable();
            
            $table->timestamp('scheduled_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['status', 'priority', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scrape_jobs');
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('collection_products');
        Schema::dropIfExists('collections');
    }
};
