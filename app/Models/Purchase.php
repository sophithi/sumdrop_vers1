<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier',
        'total',
        'currency',
        'payment_method',
        'note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted total with currency symbol
     */
    public function getFormattedTotal(): string
    {
        return CurrencyHelper::format($this->total, $this->currency);
    }

    /**
     * Get currency symbol
     */
    public function getCurrencySymbol(): string
    {
        return CurrencyHelper::getSymbol($this->currency);
    }
}
