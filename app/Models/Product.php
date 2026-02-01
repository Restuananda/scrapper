<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopee_id',
        'shop_id',
        'seller_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'original_price',
        'discount_percent',
        'stock',
        'sold_count',
        'view_count',
        'liked_count',
        'rating',
        'rating_count',
        'image_url',
        'images',
        'brand',
        'condition',
        'location',
        'is_active',
        'sales_velocity',
        'price_trend',
        'first_seen_at',
        'last_scraped_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'sales_velocity' => 'decimal:2',
        'images' => 'array',
        'is_active' => 'boolean',
        'first_seen_at' => 'datetime',
        'last_scraped_at' => 'datetime',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function priceHistory(): HasMany
    {
        return $this->hasMany(ProductPrice::class)->orderBy('recorded_at', 'desc');
    }

    public function trackedBy(): HasMany
    {
        return $this->hasMany(TrackedProduct::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_products')
            ->withPivot('notes', 'sort_order', 'added_at')
            ->withTimestamps();
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTrending($query, int $days = 7)
    {
        return $query->where('sales_velocity', '>', 0)
            ->orderBy('sales_velocity', 'desc');
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where('name', 'ilike', "%{$keyword}%");
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public function getShopeeUrlAttribute(): string
    {
        return "https://shopee.co.id/product-i.{$this->shop_id}.{$this->shopee_id}";
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute(): ?string
    {
        if (!$this->original_price) return null;
        return 'Rp ' . number_format($this->original_price, 0, ',', '.');
    }

    public function getFormattedSoldAttribute(): string
    {
        if ($this->sold_count >= 1000000) {
            return number_format($this->sold_count / 1000000, 1) . 'jt';
        }
        if ($this->sold_count >= 1000) {
            return number_format($this->sold_count / 1000, 1) . 'rb';
        }
        return (string) $this->sold_count;
    }

    public function calculateSalesVelocity(): float
    {
        if (!$this->first_seen_at) {
            return 0;
        }

        $daysSinceFirstSeen = max(1, $this->first_seen_at->diffInDays(now()));
        return round($this->sold_count / $daysSinceFirstSeen, 2);
    }

    public function updateSalesVelocity(): void
    {
        $this->update(['sales_velocity' => $this->calculateSalesVelocity()]);
    }

    public function recordPrice(): void
    {
        $this->priceHistory()->create([
            'price' => $this->price,
            'original_price' => $this->original_price,
            'discount_percent' => $this->discount_percent,
            'stock' => $this->stock,
            'sold_count' => $this->sold_count,
        ]);
    }

    public function getLatestPriceChange(): ?array
    {
        $history = $this->priceHistory()->take(2)->get();
        
        if ($history->count() < 2) return null;

        $current = $history[0];
        $previous = $history[1];

        $change = $current->price - $previous->price;
        $percentChange = $previous->price > 0 
            ? round(($change / $previous->price) * 100, 2) 
            : 0;

        return [
            'old_price' => $previous->price,
            'new_price' => $current->price,
            'change' => $change,
            'percent_change' => $percentChange,
            'direction' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'stable'),
        ];
    }

    public function toSearchArray(): array
    {
        return [
            'id' => $this->id,
            'shopee_id' => $this->shopee_id,
            'name' => $this->name,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'discount_percent' => $this->discount_percent,
            'sold_count' => $this->sold_count,
            'rating' => $this->rating,
            'rating_count' => $this->rating_count,
            'location' => $this->location,
            'image_url' => $this->image_url,
            'category_id' => $this->category_id,
            'seller_id' => $this->seller_id,
            'seller_name' => $this->seller?->name,
            'sales_velocity' => $this->sales_velocity,
        ];
    }
}
