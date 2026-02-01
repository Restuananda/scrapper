<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TrackedProduct;
use App\Models\PriceAlert;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $trackedProducts = $user->trackedProducts()
            ->with(['product' => function ($query) {
                $query->with('seller:id,name');
            }])
            ->active()
            ->latest()
            ->get()
            ->map(fn ($tp) => [
                'id' => $tp->id,
                'product' => [
                    'id' => $tp->product->id,
                    'name' => $tp->product->name,
                    'price' => $tp->product->price,
                    'original_price' => $tp->product->original_price,
                    'image' => $tp->product->image_url,
                    'sold' => $tp->product->sold_count,
                    'seller' => $tp->product->seller?->name,
                ],
                'alias' => $tp->alias,
                'check_interval' => $tp->check_interval_hours,
                'last_checked' => $tp->last_checked_at?->diffForHumans(),
                'price_change' => $tp->product->getLatestPriceChange(),
                'alerts_count' => $tp->alerts()->where('is_active', true)->count(),
            ]);

        $stats = [
            'total' => $user->trackedProducts()->count(),
            'limit' => $user->track_slots,
            'active_alerts' => $user->priceAlerts()->where('is_active', true)->count(),
        ];

        return Inertia::render('Monitoring/TrackedProducts', [
            'trackedProducts' => $trackedProducts,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'product_id' => 'required_without:shopee_url|exists:products,id',
            'shopee_url' => 'required_without:product_id|url',
            'alias' => 'nullable|string|max:255',
            'check_interval' => 'nullable|integer|min:1|max:168', // max 1 week
        ]);

        // Check track slots
        if (!$user->hasCredits('track')) {
            return back()->with('error', 'Slot tracking habis. Upgrade paket Anda.');
        }

        // Get or create product
        if (isset($validated['shopee_url'])) {
            // TODO: Parse URL and get/create product
            return back()->with('error', 'Fitur ini sedang dalam pengembangan.');
        }

        $product = Product::findOrFail($validated['product_id']);

        // Check if already tracked
        if ($user->trackedProducts()->where('product_id', $product->id)->exists()) {
            return back()->with('error', 'Produk ini sudah dilacak.');
        }

        // Create tracked product
        $tracked = $user->trackedProducts()->create([
            'product_id' => $product->id,
            'alias' => $validated['alias'] ?? null,
            'check_interval_hours' => $validated['check_interval'] ?? 24,
            'next_check_at' => now()->addHours($validated['check_interval'] ?? 24),
        ]);

        // Record initial price
        $product->recordPrice();

        return back()->with('success', 'Produk berhasil ditambahkan ke tracking.');
    }

    public function destroy(Request $request, TrackedProduct $trackedProduct)
    {
        // Check ownership
        if ($trackedProduct->user_id !== $request->user()->id) {
            abort(403);
        }

        $trackedProduct->delete();

        return back()->with('success', 'Produk dihapus dari tracking.');
    }

    // ==========================================
    // PRICE HISTORY
    // ==========================================

    public function history(Request $request)
    {
        $user = $request->user();

        $trackedProducts = $user->trackedProducts()
            ->with(['product' => function ($query) {
                $query->with(['priceHistory' => function ($q) {
                    $q->orderBy('recorded_at', 'desc')->take(30);
                }]);
            }])
            ->active()
            ->get()
            ->map(fn ($tp) => [
                'id' => $tp->id,
                'product' => [
                    'id' => $tp->product->id,
                    'name' => $tp->product->name,
                    'price' => $tp->product->price,
                    'image' => $tp->product->image_url,
                ],
                'history' => $tp->product->priceHistory->map(fn ($p) => [
                    'date' => $p->recorded_at->format('M d'),
                    'price' => $p->price,
                ]),
            ]);

        return Inertia::render('Monitoring/PriceHistory', [
            'trackedProducts' => $trackedProducts,
        ]);
    }

    // ==========================================
    // ALERTS
    // ==========================================

    public function alerts(Request $request)
    {
        $user = $request->user();

        $alerts = $user->priceAlerts()
            ->with(['trackedProduct.product'])
            ->latest()
            ->get()
            ->map(fn ($alert) => [
                'id' => $alert->id,
                'product' => [
                    'id' => $alert->trackedProduct->product->id,
                    'name' => $alert->trackedProduct->product->name,
                    'price' => $alert->trackedProduct->product->price,
                    'image' => $alert->trackedProduct->product->image_url,
                ],
                'type' => $alert->alert_type,
                'threshold' => $alert->threshold_value,
                'is_active' => $alert->is_active,
                'notify_email' => $alert->notify_email,
                'notify_whatsapp' => $alert->notify_whatsapp,
                'last_triggered' => $alert->last_triggered_at?->diffForHumans(),
                'trigger_count' => $alert->trigger_count,
            ]);

        return Inertia::render('Monitoring/Alerts', [
            'alerts' => $alerts,
        ]);
    }

    public function storeAlert(Request $request, TrackedProduct $trackedProduct)
    {
        // Check ownership
        if ($trackedProduct->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'alert_type' => 'required|in:price_below,price_drop_percent,any_change',
            'threshold_value' => 'required_unless:alert_type,any_change|numeric|min:0',
            'notify_email' => 'boolean',
            'notify_whatsapp' => 'boolean',
        ]);

        $trackedProduct->alerts()->create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return back()->with('success', 'Alert berhasil dibuat.');
    }

    public function destroyAlert(Request $request, PriceAlert $priceAlert)
    {
        // Check ownership
        if ($priceAlert->user_id !== $request->user()->id) {
            abort(403);
        }

        $priceAlert->delete();

        return back()->with('success', 'Alert dihapus.');
    }
}
