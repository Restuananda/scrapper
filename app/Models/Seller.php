<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopee_id',
        'username',
        'name',
        'avatar',
        'rating',
        'rating_count',
        'response_rate',
        'response_time',
        'follower_count',
        'product_count',
        'location',
        'joined_date',
        'is_official_shop',
        'is_preferred_seller',
        'last_scraped_at',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'joined_date' => 'date',
        'is_official_shop' => 'boolean',
        'is_preferred_seller' => 'boolean',
        'last_scraped_at' => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getShopeeUrlAttribute(): string
    {
        return "https://shopee.co.id/shop/{$this->shopee_id}";
    }

    public function getFormattedFollowersAttribute(): string
    {
        if ($this->follower_count >= 1000000) {
            return number_format($this->follower_count / 1000000, 1) . 'jt';
        }
        if ($this->follower_count >= 1000) {
            return number_format($this->follower_count / 1000, 1) . 'rb';
        }
        return (string) $this->follower_count;
    }
}
