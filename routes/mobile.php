<?php

use App\NativeComponents\Categories;
use App\NativeComponents\Home;
use App\NativeComponents\Layouts\AppLayout;
use App\NativeComponents\Settings;
use App\NativeComponents\Statistics;
use App\NativeComponents\TransactionForm;
use App\NativeComponents\Transactions;
use Illuminate\Support\Facades\Route;

Route::nativeGroup(AppLayout::class, function () {
    Route::native('/', Home::class);
    Route::native('/transactions', Transactions::class);
    Route::native('/statistics', Statistics::class);
    Route::native('/settings', Settings::class);
});

Route::native('/transactions/add', TransactionForm::class);
Route::native('/transactions/{id}/edit', TransactionForm::class);
Route::native('/categories', Categories::class);
