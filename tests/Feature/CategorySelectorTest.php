<?php

use App\Enums\TransactionType;
use App\Models\Category;
use App\NativeComponents\CategorySelector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

it('shows a placeholder trigger and a closed sheet by default', function () {
    Native::test(CategorySelector::class)
        ->assertSee('Select category')
        ->assertSet('visible', false);
});

it('lists expense categories but not income categories when the sheet opens', function () {
    Native::test(CategorySelector::class)
        ->tap('Select category')
        ->assertSet('visible', true)
        ->assertSee('Food')
        ->assertSee('Transportation')
        ->assertDontSee('Salary');
});

it('lists income categories when the type is income', function () {
    Native::test(CategorySelector::class)
        ->set('type', TransactionType::Income->value)
        ->tap('Select category')
        ->assertSee('Salary')
        ->assertSee('Freelance')
        ->assertDontSee('Food');
});

it('selects a category and closes the sheet', function () {
    $food = Category::where('name', 'Food')->where('type', TransactionType::Expense->value)->firstOrFail();

    Native::test(CategorySelector::class)
        ->tap('Select category')
        ->tap('Food')
        ->assertSet('selectedCategoryId', $food->id)
        ->assertSet('visible', false)
        ->assertSee('Food');
});
