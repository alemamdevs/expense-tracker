<?php

namespace App\NativeComponents;

use App\Models\Transaction;
use App\Services\StatsService;
use App\Services\TransactionService;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Native\Mobile\Attributes\Computed;
use Native\Mobile\Edge\NativeComponent;

class Home extends NativeComponent
{
    /**
     * All-time income, expense, and balance totals.
     *
     * @return array{balance: int, income: int, expense: int}
     */
    #[Computed]
    public function summary(): array
    {
        return app(StatsService::class)->totals();
    }

    /**
     * The most recent transactions (up to five).
     *
     * @return array<int, Transaction>
     */
    #[Computed]
    public function recentTransactions(): array
    {
        return app(TransactionService::class)->list()->take(5)->all();
    }

    #[Computed]
    public function hasTransactions(): bool
    {
        return $this->recentTransactions !== [];
    }

    #[Computed]
    public function greeting(): string
    {
        $hour = (int) Carbon::now()->format('G');

        return match (true) {
            $hour < 12 => 'Good Morning',
            $hour < 17 => 'Good Afternoon',
            default => 'Good Evening',
        };
    }

    #[Computed]
    public function currentMonth(): string
    {
        return Carbon::now()->format('F Y');
    }

    public function render(): View
    {
        return view('native.home');
    }
}
