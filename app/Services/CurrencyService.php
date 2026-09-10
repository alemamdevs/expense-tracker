<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Setting;
use App\Support\Money;
use Illuminate\Support\Collection;

class CurrencyService
{
    /**
     * All available currencies, in seed order (default BDT first).
     *
     * @return Collection<int, Currency>
     */
    public function list(): Collection
    {
        return Currency::query()->orderBy('id')->get();
    }

    /**
     * The active display currency, falling back to BDT.
     */
    public function active(): Currency
    {
        return Money::activeCurrency();
    }

    /**
     * Set the active display currency and persist the selection.
     */
    public function setActive(Currency|string $currency): Currency
    {
        if (is_string($currency)) {
            $currency = Currency::query()->where('code', strtoupper($currency))->firstOrFail();
        }

        Setting::setValue(Money::ACTIVE_CURRENCY_KEY, $currency->code);

        return $currency;
    }
}
