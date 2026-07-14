<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Get the currency symbol
     */
    public static function getSymbol(string $currency): string
    {
        return match($currency) {
            'khr' => '៛',
            'usd' => '$',
            default => '$',
        };
    }

    /**
     * Format price with currency symbol
     */
    public static function format(float|int $amount, string $currency = 'usd'): string
    {
        $symbol = self::getSymbol($currency);
        $decimals = self::getDecimals($currency);
        
        return $symbol . number_format($amount, $decimals);
    }

    /**
     * Get decimal places for currency
     */
    public static function getDecimals(string $currency): int
    {
        return match($currency) {
            'khr' => 0,
            'usd' => 2,
            default => 2,
        };
    }

    /**
     * Format just the numeric value with proper decimals
     */
    public static function formatNumber(float|int $amount, string $currency = 'usd'): string
    {
        $decimals = self::getDecimals($currency);
        return number_format($amount, $decimals);
    }

    /**
     * Validate currency code
     */
    public static function isValid(string $currency): bool
    {
        return in_array(strtolower($currency), ['usd', 'khr']);
    }

    /**
     * Get all available currencies
     */
    public static function getAvailable(): array
    {
        return ['usd', 'khr'];
    }
}
