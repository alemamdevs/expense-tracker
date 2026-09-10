<?php

namespace App\NativeComponents;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Support\Money;
use Illuminate\View\View;
use Native\Mobile\Attributes\Computed;
use Native\Mobile\Edge\NativeComponent;

class TransactionItem extends NativeComponent
{
    public ?Transaction $transaction = null;

    #[Computed]
    public function categoryName(): string
    {
        return $this->transaction?->category?->name ?? 'Uncategorized';
    }

    #[Computed]
    public function categoryIcon(): string
    {
        return $this->transaction?->category?->icon ?? 'tag';
    }

    #[Computed]
    public function isIncome(): bool
    {
        return $this->transaction?->type === TransactionType::Income;
    }

    #[Computed]
    public function amountText(): string
    {
        if ($this->transaction === null) {
            return '';
        }

        $formatted = Money::format($this->transaction->amount);

        return $this->isIncome() ? '+'.$formatted : '-'.$formatted;
    }

    #[Computed]
    public function amountColor(): string
    {
        return $this->isIncome() ? 'text-theme-success' : 'text-theme-destructive';
    }

    #[Computed]
    public function dateText(): string
    {
        return $this->transaction?->transaction_date?->format('M j, Y') ?? '';
    }

    #[Computed]
    public function note(): ?string
    {
        return $this->transaction?->note;
    }

    public function render(): View
    {
        return view('native.transaction-item');
    }
}
