<?php

use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

it('renders the add form with a type selector and category placeholder', function () {
    Native::visit('/transactions/add')
        ->assertSee('Add Transaction')
        ->assertSee('Select category')
        ->assertSet('typeIndex', 1);
});

it('hydrates the form when editing an existing transaction', function () {
    $transaction = Transaction::factory()->expense()->create([
        'amount' => 125000,
        'note' => 'Lunch',
        'transaction_date' => '2026-09-01',
    ]);

    Native::visit("/transactions/{$transaction->id}/edit")
        ->assertSee('Edit Transaction')
        ->assertSet('amount', 125000)
        ->assertSet('categoryId', $transaction->category_id)
        ->assertSet('note', 'Lunch')
        ->assertSet('date', '2026-09-01')
        ->assertSet('typeIndex', 1);
});

it('shows validation errors and creates nothing when required fields are missing', function () {
    Native::visit('/transactions/add')
        ->call('save')
        ->assertSee('must be at least 1')
        ->assertSee('category id field is required');

    expect(Transaction::count())->toBe(0);
});

it('creates a transaction when the form is valid', function () {
    $category = Category::factory()->expense()->create();

    Native::visit('/transactions/add')
        ->set('amount', 25000)
        ->set('categoryId', $category->id)
        ->set('date', '2026-09-10')
        ->set('note', 'Groceries')
        ->call('save');

    $this->assertDatabaseHas('transactions', [
        'category_id' => $category->id,
        'type' => TransactionType::Expense->value,
        'amount' => 25000,
        'note' => 'Groceries',
    ]);

    expect(Transaction::latest('id')->first()->transaction_date->toDateString())->toBe('2026-09-10');
});

it('updates the transaction when editing and saving', function () {
    $transaction = Transaction::factory()->expense()->create(['amount' => 10000]);

    Native::visit("/transactions/{$transaction->id}/edit")
        ->set('amount', 99900)
        ->call('save');

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'amount' => 99900,
    ]);
});
