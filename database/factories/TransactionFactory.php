<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'type' => fake()->randomElement([TransactionType::Income, TransactionType::Expense]),
            'amount' => fake()->numberBetween(100, 1000000),
            'note' => fake()->optional()->sentence(),
            'transaction_date' => fake()->date(),
        ];
    }

    /**
     * Indicate that the transaction is income, with a matching income category.
     */
    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::Income,
            'category_id' => Category::factory()->income(),
        ]);
    }

    /**
     * Indicate that the transaction is an expense, with a matching expense category.
     */
    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::Expense,
            'category_id' => Category::factory()->expense(),
        ]);
    }
}
