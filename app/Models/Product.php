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
        'stock',
        'size',
        'unit',
        'pack_quantity',
        'price_khr_piece',
        'price_usd_piece',
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

    /**
     * Whether this product is sold as a sealed case/pack rather than a single piece
     */
    public function isCase(): bool
    {
        return $this->unit === 'case';
    }

    /**
     * Human-readable unit label, e.g. "Case (24/pack)" or "Piece"
     */
    public function getUnitLabel(): string
    {
        if ($this->isCase() && $this->pack_quantity) {
            return __('common.case') . " ({$this->pack_quantity}/" . __('common.pack') . ')';
        }

        return $this->isCase() ? __('common.case') : __('common.piece');
    }

    /**
     * Whether individual pieces can be sold out of a case, alongside the whole case.
     * Opt-in per product: set by giving the product a piece price.
     */
    public function sellsByPiece(): bool
    {
        return $this->isCase() && $this->price_khr_piece !== null;
    }

    /**
     * Price for a given sale unit ('case' or 'piece'), regardless of the product's
     * default unit. For non-case products (or a case sold whole) this is just the
     * normal price; for a case product sold by the piece, it's the piece price.
     */
    public function getPriceForUnit(string $currency, string $saleUnit): float
    {
        if ($saleUnit === 'piece' && $this->isCase()) {
            return match ($currency) {
                'khr' => (float) ($this->price_khr_piece ?? 0),
                default => (float) ($this->price_usd_piece ?? round(($this->price_khr_piece ?? 0) / 4100, 2)),
            };
        }

        return $this->getPriceByCurrency($currency);
    }

    /**
     * How many base stock units a single sale of this unit consumes — the case's
     * pack size when sold whole, otherwise 1 (a single piece, or a non-case product).
     */
    public function unitsPerSale(string $saleUnit): int
    {
        if ($saleUnit === 'case' && $this->isCase() && $this->pack_quantity) {
            return $this->pack_quantity;
        }

        return 1;
    }

    /**
     * Stock is always tracked in base units (pieces). For case products, show it
     * in case-and-piece terms (e.g. "9 cases + 15 pcs") since that's how staff think
     * about it; other products just show the raw count.
     */
    public function stockDisplay(): string
    {
        if (! $this->isCase() || ! $this->pack_quantity) {
            return (string) $this->stock;
        }

        $cases = intdiv($this->stock, $this->pack_quantity);
        $remainder = $this->stock % $this->pack_quantity;

        if ($cases === 0) {
            return $remainder . ' ' . __($remainder === 1 ? 'common.piece' : 'common.pieces');
        }

        $label = $cases . ' ' . __($cases === 1 ? 'common.case' : 'common.cases');

        if ($remainder > 0) {
            $label .= ' + ' . $remainder . ' ' . __($remainder === 1 ? 'common.piece' : 'common.pieces');
        }

        return $label;
    }

    /**
     * Whether stock is below the low-stock threshold (matches the dashboard's low-stock alert)
     */
    public function isLowStock(int $threshold = 10): bool
    {
        return $this->stock < $threshold;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }
}
