<?php
// =====================================================
// app/Models/Collection.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
        'public_slug',
        'product_count',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if ($collection->is_public && !$collection->public_slug) {
                $collection->public_slug = Str::slug($collection->name) . '-' . Str::random(8);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'collection_products')
            ->withPivot('notes', 'sort_order', 'added_at')
            ->orderBy('collection_products.sort_order');
    }

    public function addProduct(Product $product, string $notes = null): void
    {
        if (!$this->products()->where('product_id', $product->id)->exists()) {
            $this->products()->attach($product->id, [
                'notes' => $notes,
                'sort_order' => $this->products()->count(),
                'added_at' => now(),
            ]);
            $this->increment('product_count');
        }
    }

    public function removeProduct(Product $product): void
    {
        if ($this->products()->detach($product->id)) {
            $this->decrement('product_count');
        }
    }

    public function getPublicUrlAttribute(): ?string
    {
        if (!$this->is_public || !$this->public_slug) return null;
        return url("/collections/{$this->public_slug}");
    }
}
