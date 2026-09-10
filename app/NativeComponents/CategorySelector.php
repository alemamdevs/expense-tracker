<?php

namespace App\NativeComponents;

use App\Enums\TransactionType;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Native\Mobile\Attributes\Computed;
use Native\Mobile\Edge\NativeComponent;

class CategorySelector extends NativeComponent
{
    /**
     * The transaction type whose categories are shown ('income' | 'expense').
     */
    public string $type = 'expense';

    /**
     * The currently selected category id, supplied by the parent.
     */
    public ?int $selectedCategoryId = null;

    /**
     * Whether the bottom sheet is open.
     */
    public bool $visible = false;

    public function open(): void
    {
        $this->visible = true;
    }

    public function dismiss(): void
    {
        $this->visible = false;
    }

    /**
     * Select a category, close the sheet, and emit a `select` event up.
     */
    public function select(int $categoryId): void
    {
        $this->selectedCategoryId = $categoryId;
        $this->visible = false;
        $this->emit('select', $categoryId);
    }

    /**
     * Categories of the current type, ordered by name.
     *
     * @return Collection<int, Category>
     */
    #[Computed]
    public function categories(): Collection
    {
        return app(CategoryService::class)->list($this->transactionType());
    }

    #[Computed]
    public function selectedCategory(): ?Category
    {
        if ($this->selectedCategoryId === null) {
            return null;
        }

        return $this->categories->firstWhere('id', $this->selectedCategoryId);
    }

    protected function transactionType(): TransactionType
    {
        return TransactionType::tryFrom($this->type) ?? TransactionType::Expense;
    }

    public function render(): View
    {
        return view('native.category-selector');
    }
}
