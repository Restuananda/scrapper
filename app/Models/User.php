<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'phone',
        'company',
        'timezone',
        'search_credits',
        'track_slots',
        'api_calls',
        'export_quota',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status', 'active');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function trackedProducts(): HasMany
    {
        return $this->hasMany(TrackedProduct::class);
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function priceAlerts(): HasMany
    {
        return $this->hasMany(PriceAlert::class);
    }

    // ==========================================
    // SUBSCRIPTION HELPERS
    // ==========================================

    public function hasActiveSubscription(): bool
    {
        return $this->subscription !== null;
    }

    public function getPlan(): ?Plan
    {
        return $this->subscription?->plan;
    }

    public function isOnPlan(string $planName): bool
    {
        return $this->getPlan()?->name === $planName;
    }

    public function canAccessFeature(string $feature): bool
    {
        $plan = $this->getPlan();
        if (!$plan) return false;
        return $plan->hasFeature($feature);
    }

    // ==========================================
    // CREDIT HELPERS
    // ==========================================

    public function hasCredits(string $type, int $amount = 1): bool
    {
        return match ($type) {
            'search' => $this->search_credits >= $amount,
            'track' => $this->track_slots >= $amount,
            'api' => $this->api_calls >= $amount,
            'export' => $this->export_quota >= $amount,
            default => false,
        };
    }

    public function deductCredits(string $type, int $amount = 1, string $description = null): bool
    {
        $field = match ($type) {
            'search' => 'search_credits',
            'track' => 'track_slots',
            'api' => 'api_calls',
            'export' => 'export_quota',
            default => null,
        };

        if (!$field || $this->$field < $amount) {
            return false;
        }

        $this->decrement($field, $amount);

        // Log transaction
        $this->creditTransactions()->create([
            'type' => $type,
            'amount' => -$amount,
            'balance_after' => $this->$field,
            'description' => $description ?? "Used {$amount} {$type} credit(s)",
        ]);

        return true;
    }

    public function addCredits(string $type, int $amount, string $description = null, string $referenceType = null, int $referenceId = null): void
    {
        $field = match ($type) {
            'search' => 'search_credits',
            'track' => 'track_slots',
            'api' => 'api_calls',
            'export' => 'export_quota',
            default => null,
        };

        if (!$field) return;

        $this->increment($field, $amount);

        $this->creditTransactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $this->$field,
            'description' => $description ?? "Added {$amount} {$type} credit(s)",
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }

    public function getCreditsArray(): array
    {
        return [
            'search' => $this->search_credits,
            'track' => $this->track_slots,
            'api' => $this->api_calls,
            'export' => $this->export_quota,
        ];
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        // Generate Gravatar URL
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($names, 0, 2) as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return $initials;
    }
}
