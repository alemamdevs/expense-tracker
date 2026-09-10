<?php

namespace Database\Seeders;

use App\Enums\TransactionType;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The default categories (name, type, icon).
     *
     * @return array<int, array{name: string, type: string, icon: string}>
     */
    public function defaults(): array
    {
        return [
            // Expense categories
            ['name' => 'Food', 'type' => TransactionType::Expense->value, 'icon' => 'restaurant'],
            ['name' => 'Transportation', 'type' => TransactionType::Expense->value, 'icon' => 'directions_car'],
            ['name' => 'Shopping', 'type' => TransactionType::Expense->value, 'icon' => 'shopping_cart'],
            ['name' => 'Bills', 'type' => TransactionType::Expense->value, 'icon' => 'receipt'],
            ['name' => 'Entertainment', 'type' => TransactionType::Expense->value, 'icon' => 'movie'],
            ['name' => 'Health', 'type' => TransactionType::Expense->value, 'icon' => 'health_and_safety'],
            ['name' => 'Education', 'type' => TransactionType::Expense->value, 'icon' => 'school'],
            ['name' => 'Others', 'type' => TransactionType::Expense->value, 'icon' => 'more_horiz'],

            // Income categories
            ['name' => 'Salary', 'type' => TransactionType::Income->value, 'icon' => 'payments'],
            ['name' => 'Freelance', 'type' => TransactionType::Income->value, 'icon' => 'laptop'],
            ['name' => 'Business', 'type' => TransactionType::Income->value, 'icon' => 'store'],
            ['name' => 'Investment', 'type' => TransactionType::Income->value, 'icon' => 'trending_up'],
            ['name' => 'Gift', 'type' => TransactionType::Income->value, 'icon' => 'card_giftcard'],
            ['name' => 'Others', 'type' => TransactionType::Income->value, 'icon' => 'more_horiz'],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->defaults() as $attributes) {
            Category::firstOrCreate(
                ['name' => $attributes['name'], 'type' => $attributes['type']],
                $attributes,
            );
        }
    }
}
