<?php

namespace App\NativeComponents;

use App\Support\Money;
use Illuminate\View\View;
use Native\Mobile\Attributes\Computed;
use Native\Mobile\Edge\NativeComponent;

class AmountInput extends NativeComponent
{
    /**
     * The amount in minor units (paisa), supplied by the parent.
     */
    public int $minorUnits = 0;

    /**
     * Field label shown above the input.
     */
    public string $label = 'Amount';

    /**
     * Placeholder shown while the input is empty. When null, a
     * currency-aware placeholder is derived from the active currency.
     */
    public ?string $placeholder = null;

    /**
     * The editable text in major units, two-way bound to the native input.
     */
    public string $amount = '';

    public function mount(): void
    {
        $this->amount = $this->majorFromMinor($this->minorUnits);
    }

    /**
     * Fires on each edit: convert the typed major-unit text to minor units
     * and emit it up to the parent as a `change` event.
     */
    public function updatedAmount(string $value): void
    {
        $this->emit('change', $this->minorFromMajor($value));
    }

    #[Computed]
    public function symbol(): string
    {
        return Money::activeCurrency()->symbol;
    }

    #[Computed]
    public function placeholderText(): string
    {
        if ($this->placeholder !== null && $this->placeholder !== '') {
            return $this->placeholder;
        }

        $currency = Money::activeCurrency();

        return '0'.($currency->decimal_places > 0
            ? '.'.str_repeat('0', $currency->decimal_places)
            : '');
    }

    /**
     * Parse an editable major-unit string into minor units.
     */
    private function minorFromMajor(string $text): int
    {
        $clean = trim($text);

        if ($clean === '') {
            return 0;
        }

        return Money::toMinor($clean, Money::activeCurrency());
    }

    /**
     * Format a minor-unit value as an editable major-unit string
     * (plain decimal, no symbol or thousands separators).
     */
    private function majorFromMinor(int $minor): string
    {
        if ($minor === 0) {
            return '';
        }

        $currency = Money::activeCurrency();

        return number_format(
            $minor / (10 ** $currency->decimal_places),
            $currency->decimal_places,
            '.',
            '',
        );
    }

    public function render(): View
    {
        return view('native.amount-input');
    }
}
