<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'key',
        'key_preview',
        'permissions',
        'rate_limit_per_minute',
        'last_used_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'permissions' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'key',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generate(User $user, string $name, array $permissions = []): array
    {
        $plainKey = 'sip_' . Str::random(40);
        
        $apiKey = self::create([
            'user_id' => $user->id,
            'name' => $name,
            'key' => hash('sha256', $plainKey),
            'key_preview' => substr($plainKey, 0, 10) . '...',
            'permissions' => $permissions,
        ]);

        return [
            'api_key' => $apiKey,
            'plain_key' => $plainKey, // Only shown once!
        ];
    }

    public static function findByKey(string $plainKey): ?self
    {
        return self::where('key', hash('sha256', $plainKey))
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    public function hasPermission(string $permission): bool
    {
        if (empty($this->permissions)) return true; // No restrictions
        return in_array($permission, $this->permissions);
    }

    public function markUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function revoke(): void
    {
        $this->update(['is_active' => false]);
    }
}
