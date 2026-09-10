<?php

namespace App\NativeComponents;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Native\Mobile\Attributes\Computed;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\Facades\Dialog;

class TransactionForm extends NativeComponent
{
    public ?int $transactionId = null;

    /** 0 = income, 1 = expense. */
    public int $typeIndex = 1;

    /** Amount in minor units (paisa). */
    public int $amount = 0;

    public ?int $categoryId = null;

    /** Transaction date as a wall-clock `Y-m-d` string. */
    public string $date = '';

    public string $note = '';

    /** @var array<string, string> Field → first validation message. */
    public array $errors = [];

    public function mount(): void
    {
        $id = $this->param('id');

        if ($id !== null) {
            $this->hydrate(Transaction::findOrFail((int) $id));
        }

        if ($this->date === '') {
            $this->date = Carbon::now()->toDateString();
        }
    }

    #[Computed]
    public function isEditing(): bool
    {
        return $this->transactionId !== null;
    }

    #[Computed]
    public function title(): string
    {
        return $this->isEditing() ? 'Edit Transaction' : 'Add Transaction';
    }

    #[Computed]
    public function type(): string
    {
        return $this->typeIndex === 0
            ? TransactionType::Income->value
            : TransactionType::Expense->value;
    }

    /** @return array<int, string> */
    #[Computed]
    public function typeOptions(): array
    {
        return ['Income', 'Expense'];
    }

    public function updatedTypeIndex(int $index): void
    {
        $this->categoryId = null;
        unset($this->errors['category_id']);
    }

    public function amountChanged(int $minorUnits): void
    {
        $this->amount = $minorUnits;
        unset($this->errors['amount']);
    }

    public function categorySelected(int $id): void
    {
        $this->categoryId = $id;
        unset($this->errors['category_id']);
    }

    public function errorFor(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }

    public function save(): void
    {
        $data = $this->validatedData();

        if ($data === null) {
            return;
        }

        if ($this->isEditing()) {
            app(TransactionService::class)->update($this->transaction(), $data);
            Dialog::toast('Transaction updated');
        } else {
            app(TransactionService::class)->create($data);
            Dialog::toast('Transaction added');
        }

        $this->back();
    }

    protected function transaction(): Transaction
    {
        return Transaction::findOrFail($this->transactionId);
    }

    protected function hydrate(Transaction $transaction): void
    {
        $this->transactionId = $transaction->id;
        $this->typeIndex = $transaction->type === TransactionType::Income ? 0 : 1;
        $this->amount = $transaction->amount;
        $this->categoryId = $transaction->category_id;
        $this->date = $transaction->transaction_date->toDateString();
        $this->note = $transaction->note ?? '';
    }

    /**
     * Validate the form and return validated data, or null with inline
     * errors set when invalid.
     *
     * @return array<string, mixed>|null
     */
    protected function validatedData(): ?array
    {
        $validator = Validator::make($this->formData(), [
            'type' => ['required', Rule::enum(TransactionType::class)],
            'amount' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'transaction_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            $this->errors = collect($validator->errors()->messages())
                ->map(fn (array $messages): string => $messages[0])
                ->all();

            return null;
        }

        $this->errors = [];

        return $validator->validated();
    }

    /** @return array<string, mixed> */
    protected function formData(): array
    {
        return [
            'type' => $this->type,
            'amount' => $this->amount,
            'category_id' => $this->categoryId,
            'transaction_date' => $this->date,
            'note' => $this->note ?: null,
        ];
    }

    public function render(): View
    {
        return view('native.transaction-form');
    }
}
