<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SellerController;
use App\Http\Controllers\Api\V1\ExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API v1
Route::prefix('v1')->middleware(['api.auth', 'api.credits'])->group(function () {
    
    // Products
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/trending', [ProductController::class, 'trending']);
    Route::get('/products/{shopee_id}', [ProductController::class, 'show']);
    
    // Sellers
    Route::get('/sellers/{shopee_id}', [SellerController::class, 'show']);
    Route::get('/sellers/{shopee_id}/products', [SellerController::class, 'products']);
    
    // Categories
    Route::get('/categories', function () {
        return \App\Models\Category::roots()
            ->with('children')
            ->get();
    });
    
    // Exports
    Route::post('/exports', [ExportController::class, 'create']);
    Route::get('/exports/{job_id}', [ExportController::class, 'status']);
    
    // User
    Route::get('/me', function (Request $request) {
        $user = $request->user();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'credits' => $user->getCreditsArray(),
        ];
    });
});
