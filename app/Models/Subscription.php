<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'cancelled_at',
        'gateway',
        'gateway_subscription_id',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_TRIALING = 'trialing';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isTrialing(): bool
    {
        return $this->status === self::STATUS_TRIALING && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isOnGracePeriod(): bool
    {
        return $this->isCancelled() && 
               $this->current_period_end && 
               $this->current_period_end->isFuture();
    }

    public function daysUntilRenewal(): ?int
    {
        if (!$this->current_period_end) return null;
        return now()->diffInDays($this->current_period_end, false);
    }

    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);
    }

    public function resume(): void
    {
        if (!$this->isOnGracePeriod()) return;
        
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'cancelled_at' => null,
        ]);
    }

    public function renew(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ]);

        // Add credits to user
        $plan = $this->plan;
        $user = $this->user;
        
        $user->addCredits('search', $plan->search_credits, 'Monthly renewal', 'subscription', $this->id);
        $user->addCredits('track', $plan->track_slots, 'Monthly renewal', 'subscription', $this->id);
        $user->addCredits('api', $plan->api_calls, 'Monthly renewal', 'subscription', $this->id);
        $user->addCredits('export', $plan->export_quota, 'Monthly renewal', 'subscription', $this->id);
    }
}
