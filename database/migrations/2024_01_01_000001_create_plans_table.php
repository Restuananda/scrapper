<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('display_name', 100);
            $table->decimal('price', 12, 2);
            $table->decimal('price_yearly', 12, 2)->nullable();
            
            // Monthly Quotas
            $table->integer('search_credits')->default(0);
            $table->integer('track_slots')->default(0);
            $table->integer('api_calls')->default(0);
            $table->integer('export_quota')->default(0);
            
            // Features JSON
            $table->json('features');
            
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
