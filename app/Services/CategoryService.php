<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    /**
     * Create a category.
     *
     * @param  array{name: string, type: TransactionType|string, icon?: string|null, color?: string|null}  $data
     *
     * @throws ValidationException
     */
    public function create(array $data): Category
    {
        return Category::create($this->validated($data));
    }

    /**
     * Update an existing category.
     *
     * @param  array{name: string, type: TransactionType|string, icon?: string|null, color?: string|null}  $data
     *
     * @throws ValidationException
     */
    public function update(Category $category, array $data): Category
    {
        $category->update($this->validated($data));

        return $category->refresh();
    }

    /**
     * Delete a category, refusing when transactions still reference it.
     *
     * Returns false (without deleting) when the category is in use.
     */
    public function delete(Category $category): bool
    {
        if ($this->hasTransactions($category)) {
            return false;
        }

        return (bool) $category->delete();
    }

    /**
     * Determine whether the category still has transactions referencing it.
     */
    public function hasTransactions(Category $category): bool
    {
        return $category->transactions()->exists();
    }

    /**
     * List categories, optionally filtered by type.
     *
     * @return Collection<int, Category>
     */
    public function list(?TransactionType $type = null): Collection
    {
        return Category::query()
            ->when($type, fn ($query) => $query->where('type', $type->value))
            ->orderBy('type')
            ->orderBy('name')
            ->get();
    }

    /**
     * Validate category input and return the validated attributes.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    protected function validated(array $data): array
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(TransactionType::class)],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
        ])->validate();
    }
}
