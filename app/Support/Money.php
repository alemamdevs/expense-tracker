<?php

namespace App\Support;

use App\Models\Currency;
use App\Models\Setting;

class Money
{
    /**
     * The settings key that stores the active currency code.
     */
    public const ACTIVE_CURRENCY_KEY = 'active_currency';

    /**
     * Format an amount in minor units (paisa) as a display string using the
     * given currency (or the active currency when omitted).
     */
    public static function format(int $minorUnits, ?Currency $currency = null): string
    {
        $currency ??= static::activeCurrency();

        return $currency->symbol.' '.number_format(
            static::toMajor($minorUnits, $currency),
            $currency->decimal_places,
        );
    }

    /**
     * Resolve the active currency from settings, falling back to BDT.
     */
    public static function activeCurrency(): Currency
    {
        $code = Setting::getValue(static::ACTIVE_CURRENCY_KEY, Currency::DEFAULT_CODE);

        return Currency::where('code', $code)->first() ?? static::fallback();
    }

    /**
     * Convert a major-unit amount to minor units (paisa).
     */
    public static function toMinor(float|string $major, Currency $currency): int
    {
        return (int) round((float) $major * (10 ** $currency->decimal_places));
    }

    /**
     * Convert minor units (paisa) to a major-unit amount.
     */
    public static function toMajor(int $minorUnits, Currency $currency): float
    {
        return $minorUnits / (10 ** $currency->decimal_places);
    }

    /**
     * A transient BDT currency used when the currency table is not yet seeded.
     */
    protected static function fallback(): Currency
    {
        return new Currency([
            'code' => Currency::DEFAULT_CODE,
            'name' => 'Bangladeshi Taka',
            'symbol' => '৳',
            'decimal_places' => 2,
        ]);
    }
}
