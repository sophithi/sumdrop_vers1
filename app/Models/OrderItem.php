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
        'sale_unit',
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

    /**
     * Short label for how this line was sold, e.g. "Case" or "Piece" — only
     * meaningful (and shown) for a case product that was sold by the piece or
     * whole; blank for ordinary single-unit products and pre-feature rows.
     */
    public function saleUnitLabel(): string
    {
        if (! $this->sale_unit || ! $this->product?->isCase()) {
            return '';
        }

        return $this->sale_unit === 'case' ? Product::unitName($this->product->unit) : __('common.piece');
    }
}
