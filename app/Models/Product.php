<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'price',
        'price_usd',
        'price_khr',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get formatted USD price with symbol
     */
    public function getFormattedPriceUsd(): string
    {
        $price = $this->price_usd ?? $this->price ?? 0;
        return CurrencyHelper::format($price, 'usd');
    }

    /**
     * Get formatted KHR price with symbol
     */
    public function getFormattedPriceKhr(): string
    {
        $price = $this->price_khr ?? $this->price ?? 0;
        return CurrencyHelper::format($price, 'khr');
    }

    /**
     * Get price by currency
     */
    public function getPriceByCurrency(string $currency): float
    {
        return match($currency) {
            'khr' => $this->price_khr ?? $this->price ?? 0,
            'usd' => $this->price_usd ?? $this->price ?? 0,
            default => $this->price ?? 0,
        };
    }

    /**
     * Get formatted price by currency
     */
    public function getFormattedPrice(string $currency = 'usd'): string
    {
        $price = $this->getPriceByCurrency($currency);
        return CurrencyHelper::format($price, $currency);
    }

    /**
     * Get just the numeric price by currency (no symbol)
     */
    public function getFormattedNumberPrice(string $currency = 'usd'): string
    {
        $price = $this->getPriceByCurrency($currency);
        return CurrencyHelper::formatNumber($price, $currency);
    }

    /**
     * Get the full URL path for the product image
     */
    public function getImageUrl(): ?string
    {
        if (!$this->image) {
            return null;
        }
        
        return asset('storage/' . $this->image);
    }

    /**
     * Check if product has an image
     */
    public function hasImage(): bool
    {
        return !empty($this->image);
    }
}
