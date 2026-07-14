<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get unit price formatted with currency symbol
     */
    public function getFormattedPrice(): string
    {
        $currency = $this->order->currency ?? 'usd';
        return CurrencyHelper::format($this->price, $currency);
    }

    /**
     * Get line total (price * quantity) formatted with currency
     */
    public function getFormattedTotal(): string
    {
        $currency = $this->order->currency ?? 'usd';
        $total = $this->price * $this->quantity;
        return CurrencyHelper::format($total, $currency);
    }

    /**
     * Get line total numeric value
     */
    public function getLineTotal(): float
    {
        return $this->price * $this->quantity;
    }
}
