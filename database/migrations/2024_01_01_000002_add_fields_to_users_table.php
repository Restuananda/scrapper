<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('password');
            $table->string('avatar', 500)->nullable()->after('google_id');
            $table->string('phone', 20)->nullable()->after('avatar');
            $table->string('company')->nullable()->after('phone');
            $table->string('timezone', 50)->default('Asia/Jakarta')->after('company');
            
            // Credits & Quotas
            $table->integer('search_credits')->default(0);
            $table->integer('track_slots')->default(0);
            $table->integer('api_calls')->default(0);
            $table->integer('export_quota')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id', 'avatar', 'phone', 'company', 'timezone',
                'search_credits', 'track_slots', 'api_calls', 'export_quota'
            ]);
        });
    }
};
