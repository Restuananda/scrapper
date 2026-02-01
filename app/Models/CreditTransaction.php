<?php
// =====================================================
// app/Models/CreditTransaction.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'reference_type',
        'reference_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isDebit(): bool
    {
        return $this->amount < 0;
    }

    public function isCredit(): bool
    {
        return $this->amount > 0;
    }

    public function getFormattedAmountAttribute(): string
    {
        $prefix = $this->amount > 0 ? '+' : '';
        return $prefix . $this->amount;
    }
}
