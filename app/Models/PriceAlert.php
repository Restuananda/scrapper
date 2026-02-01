<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracked_product_id',
        'alert_type',
        'threshold_value',
        'notify_email',
        'notify_whatsapp',
        'notify_webhook',
        'webhook_url',
        'is_active',
        'last_triggered_at',
        'trigger_count',
    ];

    protected $casts = [
        'threshold_value' => 'decimal:2',
        'notify_email' => 'boolean',
        'notify_whatsapp' => 'boolean',
        'notify_webhook' => 'boolean',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
    ];

    const TYPE_PRICE_BELOW = 'price_below';
    const TYPE_PRICE_DROP_PERCENT = 'price_drop_percent';
    const TYPE_ANY_CHANGE = 'any_change';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trackedProduct(): BelongsTo
    {
        return $this->belongsTo(TrackedProduct::class);
    }

    public function shouldTrigger(float $oldPrice, float $newPrice): bool
    {
        if (!$this->is_active) return false;

        return match ($this->alert_type) {
            self::TYPE_PRICE_BELOW => $newPrice <= $this->threshold_value,
            self::TYPE_PRICE_DROP_PERCENT => $this->calculateDropPercent($oldPrice, $newPrice) >= $this->threshold_value,
            self::TYPE_ANY_CHANGE => $oldPrice !== $newPrice,
            default => false,
        };
    }

    private function calculateDropPercent(float $oldPrice, float $newPrice): float
    {
        if ($oldPrice <= 0) return 0;
        return (($oldPrice - $newPrice) / $oldPrice) * 100;
    }

    public function markTriggered(): void
    {
        $this->update([
            'last_triggered_at' => now(),
            'trigger_count' => $this->trigger_count + 1,
        ]);
    }
}
