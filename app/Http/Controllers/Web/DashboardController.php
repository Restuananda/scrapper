<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TrackedProduct;
use App\Models\PriceAlert;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get stats
        $stats = [
            'searchCredits' => $user->search_credits,
            'searchCreditsTotal' => $user->getPlan()?->search_credits ?? 500,
            'trackedProducts' => $user->trackedProducts()->active()->count(),
            'trackedProductsTotal' => $user->getPlan()?->track_slots ?? 20,
            'activeAlerts' => $user->priceAlerts()->where('is_active', true)->count(),
            'reportsGenerated' => 0, // TODO: Implement reports count
        ];

        // Get trending products
        $trendingProducts = Product::active()
            ->trending()
            ->with('seller:id,name,rating')
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'sold' => $p->sold_count,
                'rating' => $p->rating,
                'image' => $p->image_url,
                'trend' => 'up',
                'trendPercent' => rand(5, 30), // TODO: Calculate real trend
            ]);

        // Get recent price alerts
        $priceAlerts = collect(); // TODO: Get real price change alerts

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'trendingProducts' => $trendingProducts,
            'priceAlerts' => $priceAlerts,
        ]);
    }
}
