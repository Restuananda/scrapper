<?php
// =====================================================
// app/Models/TrackedProduct.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrackedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'alias',
        'notes',
        'check_interval_hours',
        'last_checked_at',
        'next_check_at',
        'is_active',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'next_check_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(PriceAlert::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDueForCheck($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('next_check_at')
                  ->orWhere('next_check_at', '<=', now());
            });
    }

    public function markChecked(): void
    {
        $this->update([
            'last_checked_at' => now(),
            'next_check_at' => now()->addHours($this->check_interval_hours),
        ]);
    }
}
