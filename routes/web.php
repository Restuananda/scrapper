<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\MonitoringController;
use App\Http\Controllers\Web\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return Inertia::render('Welcome');
});

// ==========================================
// AUTH ROUTES
// ==========================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// AUTHENTICATED ROUTES
// ==========================================

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // PRODUCT RESEARCH
    // ==========================================
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::get('/trending', [ProductController::class, 'trending'])->name('trending');
        Route::get('/categories', [ProductController::class, 'categories'])->name('categories');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    });

    // ==========================================
    // MONITORING
    // ==========================================
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/products', [MonitoringController::class, 'index'])->name('products');
        Route::post('/products', [MonitoringController::class, 'store'])->name('products.store');
        Route::delete('/products/{trackedProduct}', [MonitoringController::class, 'destroy'])->name('products.destroy');
        
        Route::get('/history', [MonitoringController::class, 'history'])->name('history');
        
        Route::get('/alerts', [MonitoringController::class, 'alerts'])->name('alerts');
        Route::post('/alerts/{trackedProduct}', [MonitoringController::class, 'storeAlert'])->name('alerts.store');
        Route::delete('/alerts/{priceAlert}', [MonitoringController::class, 'destroyAlert'])->name('alerts.destroy');
    });

    // ==========================================
    // SUBSCRIPTION & BILLING
    // ==========================================
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
        Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('checkout');
        Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::get('/success', function () {
            return Inertia::render('Subscription/Success');
        })->name('success');
    });

    // ==========================================
    // SETTINGS
    // ==========================================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', function () {
            return Inertia::render('Settings/Profile');
        })->name('profile');
        
        Route::get('/billing', [SubscriptionController::class, 'billing'])->name('billing');
        
        Route::get('/subscription', function () {
            return redirect()->route('subscription.plans');
        })->name('subscription');
    });

    // ==========================================
    // COLLECTIONS
    // ==========================================
    Route::prefix('collections')->name('collections.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Collections/Index');
        })->name('index');
    });

    // ==========================================
    // MARKET INTELLIGENCE
    // ==========================================
    Route::prefix('intelligence')->name('intelligence.')->group(function () {
        Route::get('/categories', function () {
            return Inertia::render('Intelligence/Categories');
        })->name('categories');
        
        Route::get('/sellers', function () {
            return Inertia::render('Intelligence/Sellers');
        })->name('sellers');
        
        Route::get('/brands', function () {
            return Inertia::render('Intelligence/Brands');
        })->name('brands');
    });

    // ==========================================
    // API MANAGEMENT
    // ==========================================
    Route::prefix('api-management')->name('api.')->group(function () {
        Route::get('/keys', function () {
            return Inertia::render('Api/Keys');
        })->name('keys');
        
        Route::get('/docs', function () {
            return Inertia::render('Api/Docs');
        })->name('docs');
    });

    // ==========================================
    // REPORTS
    // ==========================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Reports/Index');
        })->name('index');
        
        Route::get('/create', function () {
            return Inertia::render('Reports/Create');
        })->name('create');
    });
});

// ==========================================
// WEBHOOKS (no auth)
// ==========================================
Route::post('/webhooks/midtrans', [SubscriptionController::class, 'handleWebhook'])
    ->name('webhooks.midtrans');

// Internal scraper callback (secret protected)
Route::post('/api/internal/scrape-callback', function (\Illuminate\Http\Request $request) {
    // Verify secret
    if ($request->header('X-Scraper-Secret') !== config('services.scraper.callback_secret')) {
        abort(401);
    }

    $service = app(\App\Services\Scraper\ScraperService::class);
    $processed = $service->processSearchResults($request->input('results', []));

    return response()->json(['processed' => $processed]);
})->name('scraper.callback');
