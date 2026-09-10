<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class BalanceCard extends NativeComponent
{
    public int $balance = 0;

    public int $income = 0;

    public int $expense = 0;

    public function render(): View
    {
        return view('native.balance-card');
    }
}
