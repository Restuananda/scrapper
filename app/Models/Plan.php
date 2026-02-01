<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'price',
        'price_yearly',
        'search_credits',
        'track_slots',
        'api_calls',
        'export_quota',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getFeature(string $key, $default = null)
    {
        return data_get($this->features, $key, $default);
    }

    public function hasFeature(string $key): bool
    {
        return (bool) $this->getFeature($key);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getMonthlyPriceFromYearlyAttribute(): ?string
    {
        if (!$this->price_yearly) return null;
        $monthly = $this->price_yearly / 12;
        return 'Rp ' . number_format($monthly, 0, ',', '.');
    }

    public function getYearlySavingsPercentAttribute(): ?int
    {
        if (!$this->price_yearly) return null;
        $yearlyIfMonthly = $this->price * 12;
        $savings = (($yearlyIfMonthly - $this->price_yearly) / $yearlyIfMonthly) * 100;
        return (int) round($savings);
    }
}
