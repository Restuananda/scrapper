<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\Scraper\ScraperService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        protected ScraperService $scraperService
    ) {}

    public function search(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['q', 'category', 'price_min', 'price_max', 'sort']);

        $products = collect();

        // If search query exists, search products
        if ($request->filled('q')) {
            // Check credits
            if (!$user->hasCredits('search')) {
                return back()->with('error', 'Kredit pencarian habis. Upgrade paket Anda.');
            }

            // Search from database first
            $query = Product::query()
                ->active()
                ->search($request->q);

            // Apply filters
            if ($request->filled('category')) {
                $query->inCategory($request->category);
            }

            if ($request->filled('price_min') || $request->filled('price_max')) {
                $query->priceRange($request->price_min, $request->price_max);
            }

            // Apply sorting
            $query = match ($request->sort) {
                'sales' => $query->orderBy('sold_count', 'desc'),
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'rating' => $query->orderBy('rating', 'desc'),
                'newest' => $query->orderBy('created_at', 'desc'),
                default => $query->orderBy('sold_count', 'desc'),
            };

            $products = $query->with('seller:id,name,rating')
                ->take(50)
                ->get()
                ->map(fn ($p) => $this->formatProduct($p));

            // If no results in DB, trigger scrape
            if ($products->isEmpty()) {
                // Queue scrape job
                $this->scraperService->queueSearch($request->q, $filters);
                
                // Deduct credit
                $user->deductCredits('search', 1, "Search: {$request->q}");
            }
        }

        $categories = Category::roots()->with('children')->get();

        return Inertia::render('Products/Search', [
            'products' => $products,
            'filters' => $filters,
            'categories' => $categories,
        ]);
    }

    public function trending(Request $request)
    {
        $days = $request->input('days', 7);
        
        $products = Product::active()
            ->trending($days)
            ->with('seller:id,name,rating', 'category:id,name')
            ->take(100)
            ->get()
            ->map(fn ($p) => $this->formatProduct($p));

        $categories = Category::roots()->get();

        return Inertia::render('Products/Trending', [
            'products' => $products,
            'categories' => $categories,
            'days' => $days,
        ]);
    }

    public function categories(Request $request)
    {
        $categories = Category::roots()
            ->with(['children' => function ($query) {
                $query->withCount('products');
            }])
            ->withCount('products')
            ->get();

        return Inertia::render('Products/Categories', [
            'categories' => $categories,
        ]);
    }

    public function show(Request $request, Product $product)
    {
        $product->load(['seller', 'category', 'priceHistory' => function ($query) {
            $query->orderBy('recorded_at', 'desc')->take(30);
        }]);

        // Get similar products
        $similarProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(8)
            ->get()
            ->map(fn ($p) => $this->formatProduct($p));

        return Inertia::render('Products/Detail', [
            'product' => $this->formatProduct($product, true),
            'priceHistory' => $product->priceHistory->map(fn ($p) => [
                'date' => $p->recorded_at->format('Y-m-d'),
                'price' => $p->price,
            ]),
            'similarProducts' => $similarProducts,
        ]);
    }

    protected function formatProduct(Product $product, bool $detailed = false): array
    {
        $data = [
            'id' => $product->id,
            'shopee_id' => $product->shopee_id,
            'name' => $product->name,
            'price' => $product->price,
            'original_price' => $product->original_price,
            'discount' => $product->discount_percent,
            'sold' => $product->sold_count,
            'rating' => $product->rating,
            'rating_count' => $product->rating_count,
            'location' => $product->location,
            'image' => $product->image_url,
            'seller' => $product->seller ? [
                'id' => $product->seller->id,
                'name' => $product->seller->name,
                'rating' => $product->seller->rating,
            ] : null,
        ];

        if ($detailed) {
            $data['description'] = $product->description;
            $data['images'] = $product->images;
            $data['stock'] = $product->stock;
            $data['brand'] = $product->brand;
            $data['category'] = $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ] : null;
            $data['shopee_url'] = $product->shopee_url;
        }

        return $data;
    }
}
