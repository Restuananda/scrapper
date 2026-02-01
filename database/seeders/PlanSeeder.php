<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'starter',
                'display_name' => 'Starter',
                'price' => 99000,
                'price_yearly' => 990000, // ~17% off
                'search_credits' => 500,
                'track_slots' => 20,
                'api_calls' => 0,
                'export_quota' => 3,
                'features' => [
                    'price_history_days' => 7,
                    'check_interval_hours' => 24,
                    'export_formats' => ['csv'],
                    'alerts' => ['email'],
                    'api_access' => false,
                    'report_templates' => [],
                    'support_level' => 'email',
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'pro',
                'display_name' => 'Pro',
                'price' => 299000,
                'price_yearly' => 2990000, // ~17% off
                'search_credits' => 2000,
                'track_slots' => 100,
                'api_calls' => 1000,
                'export_quota' => 20,
                'features' => [
                    'price_history_days' => 30,
                    'check_interval_hours' => 12,
                    'export_formats' => ['csv', 'xlsx'],
                    'alerts' => ['email', 'whatsapp'],
                    'api_access' => true,
                    'report_templates' => ['basic'],
                    'support_level' => 'email',
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'business',
                'display_name' => 'Business',
                'price' => 799000,
                'price_yearly' => 7990000, // ~17% off
                'search_credits' => 10000,
                'track_slots' => 500,
                'api_calls' => 10000,
                'export_quota' => 100,
                'features' => [
                    'price_history_days' => 90,
                    'check_interval_hours' => 6,
                    'export_formats' => ['csv', 'xlsx', 'json'],
                    'alerts' => ['email', 'whatsapp', 'webhook'],
                    'api_access' => true,
                    'report_templates' => ['basic', 'advanced'],
                    'support_level' => 'priority',
                    'market_intelligence' => true,
                    'competitor_tracking' => true,
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'enterprise',
                'display_name' => 'Enterprise',
                'price' => 2499000,
                'price_yearly' => 24990000,
                'search_credits' => 50000,
                'track_slots' => 2000,
                'api_calls' => 100000,
                'export_quota' => 500,
                'features' => [
                    'price_history_days' => 365,
                    'check_interval_hours' => 1,
                    'export_formats' => ['csv', 'xlsx', 'json', 'api'],
                    'alerts' => ['email', 'whatsapp', 'webhook', 'slack'],
                    'api_access' => true,
                    'report_templates' => ['basic', 'advanced', 'custom'],
                    'support_level' => 'dedicated',
                    'market_intelligence' => true,
                    'competitor_tracking' => true,
                    'custom_reports' => true,
                    'sla' => true,
                ],
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
