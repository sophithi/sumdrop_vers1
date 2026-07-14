<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'tax',
        'total',
        'status',
        'payment_method',
        'currency',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getReceiptNumberAttribute(): int
    {
        return self::whereDate('created_at', $this->created_at->toDateString())
            ->where('id', '<=', $this->id)
            ->count();
    }

    /**
     * Get formatted total with currency symbol
     */
    public function getFormattedTotal(): string
    {
        return CurrencyHelper::format($this->total, $this->currency);
    }

    /**
     * Get formatted subtotal with currency symbol
     */
    public function getFormattedSubtotal(): string
    {
        return CurrencyHelper::format($this->subtotal, $this->currency);
    }

    /**
     * Get formatted tax with currency symbol
     */
    public function getFormattedTax(): string
    {
        return CurrencyHelper::format($this->tax, $this->currency);
    }

    /**
     * Get currency symbol
     */
    public function getCurrencySymbol(): string
    {
        return CurrencyHelper::getSymbol($this->currency);
    }

    /**
     * Get currency name
     */
    public function getCurrencyName(): string
    {
        return match($this->currency) {
            'khr' => 'Khmer Riel',
            'usd' => 'US Dollar',
            default => 'Unknown',
        };
    }
}
