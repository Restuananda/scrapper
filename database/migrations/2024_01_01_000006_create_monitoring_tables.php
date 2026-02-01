<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tracked Products
        Schema::create('tracked_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->string('alias')->nullable();
            $table->text('notes')->nullable();
            
            $table->integer('check_interval_hours')->default(24);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('next_check_at')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->unique(['user_id', 'product_id']);
        });

        // Price Alerts
        Schema::create('price_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tracked_product_id')->constrained()->onDelete('cascade');
            
            $table->string('alert_type', 20); // price_below, price_drop_percent, any_change
            $table->decimal('threshold_value', 15, 2)->nullable();
            
            $table->boolean('notify_email')->default(true);
            $table->boolean('notify_whatsapp')->default(false);
            $table->boolean('notify_webhook')->default(false);
            $table->string('webhook_url', 500)->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_triggered_at')->nullable();
            $table->integer('trigger_count')->default(0);
            
            $table->timestamps();
        });

        // Alert Logs
        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_alert_id')->constrained()->onDelete('cascade');
            
            $table->decimal('old_price', 15, 2);
            $table->decimal('new_price', 15, 2);
            $table->string('notification_channel', 20);
            $table->boolean('sent_successfully')->default(false);
            $table->text('error_message')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
        Schema::dropIfExists('price_alerts');
        Schema::dropIfExists('tracked_products');
    }
};
