<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    /**
     * Create a transaction.
     *
     * @param  array{category_id: int, type: TransactionType|string, amount: int, note?: string|null, transaction_date: string}  $data
     *
     * @throws ValidationException
     */
    public function create(array $data): Transaction
    {
        return Transaction::create($this->validated($data));
    }

    /**
     * Update an existing transaction.
     *
     * @param  array{category_id: int, type: TransactionType|string, amount: int, note?: string|null, transaction_date: string}  $data
     *
     * @throws ValidationException
     */
    public function update(Transaction $transaction, array $data): Transaction
    {
        $transaction->update($this->validated($data));

        return $transaction->refresh();
    }

    /**
     * Delete a transaction.
     */
    public function delete(Transaction $transaction): bool
    {
        return (bool) $transaction->delete();
    }

    /**
     * List transactions, optionally filtered.
     *
     * @param  array{type?: TransactionType|string|null, category_id?: int|null, date_from?: string|null, date_to?: string|null}  $filters
     * @return Collection<int, Transaction>
     */
    public function list(array $filters = []): Collection
    {
        return $this->filteredQuery($filters)
            ->with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->get();
    }

    /**
     * Build a query for transactions with the given filters applied.
     *
     * @param  array{type?: TransactionType|string|null, category_id?: int|null, date_from?: string|null, date_to?: string|null}  $filters
     */
    protected function filteredQuery(array $filters): Builder
    {
        $query = Transaction::query();

        if (! empty($filters['type'])) {
            $query->where('type', $this->normalizeType($filters['type']));
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $filters['date_to']);
        }

        return $query;
    }

    /**
     * Validate transaction input and return the validated attributes.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    protected function validated(array $data): array
    {
        return Validator::make($data, [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'type' => ['required', Rule::enum(TransactionType::class)],
            'amount' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
            'transaction_date' => ['required', 'date'],
        ])->validate();
    }

    /**
     * Normalize a type filter to its string value.
     */
    protected function normalizeType(TransactionType|string $type): string
    {
        return $type instanceof TransactionType ? $type->value : $type;
    }
}
