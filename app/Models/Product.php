<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Unit values that mean "sold in a package with a quantity inside" — as opposed
     * to 'piece', a single loose item. The specific word just changes the label shown.
     */
    public const CASE_LIKE_UNITS = ['case', 'can', 'pack', 'box', 'glass'];

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
     * Whether this product is sold as a sealed package (case/can/pack/box) rather
     * than a single piece.
     */
    public function isCase(): bool
    {
        return in_array($this->unit, self::CASE_LIKE_UNITS, true);
    }

    /**
     * Human-readable name for a unit value, e.g. "Case", "Can", "Piece" — falls back
     * to "Piece" for 'piece' and anything unrecognized.
     */
    public static function unitName(?string $unit): string
    {
        return in_array($unit, self::CASE_LIKE_UNITS, true)
            ? __('common.' . $unit)
            : __('common.piece');
    }

    /**
     * Human-readable unit label, e.g. "Case (24)" or "Piece"
     */
    public function getUnitLabel(): string
    {
        $name = self::unitName($this->unit);

        return $this->isCase() && $this->pack_quantity
            ? "{$name} ({$this->pack_quantity})"
            : $name;
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

        $label = $cases . ' ' . self::unitName($this->unit);

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
