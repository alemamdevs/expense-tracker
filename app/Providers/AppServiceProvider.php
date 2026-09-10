<?php

namespace App\Providers;

use App\NativeComponents\AmountInput;
use App\NativeComponents\BalanceCard;
use App\NativeComponents\CategorySelector;
use App\NativeComponents\EmptyState;
use App\NativeComponents\TransactionItem;
use Illuminate\Support\ServiceProvider;
use Native\Mobile\Edge\ComponentRegistry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ComponentRegistry::components([
            'amount-input' => AmountInput::class,
            'balance-card' => BalanceCard::class,
            'category-selector' => CategorySelector::class,
            'empty-state' => EmptyState::class,
            'transaction-item' => TransactionItem::class,
        ]);
    }
}
