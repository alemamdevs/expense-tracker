<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatsService
{
    /**
     * Overall income, expense, and balance totals (optionally within a date range).
     *
     * @return array{income: int, expense: int, balance: int}
     */
    public function totals(?Carbon $from = null, ?Carbon $to = null): array
    {
        $income = $this->sumForType(TransactionType::Income, $from, $to);
        $expense = $this->sumForType(TransactionType::Expense, $from, $to);

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
        ];
    }

    /**
     * Income, expense, and balance for a single calendar month.
     *
     * @return array{income: int, expense: int, balance: int}
     */
    public function monthlySummary(int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();

        return $this->totals($start, $start->copy()->endOfMonth());
    }

    /**
     * Sum of transactions grouped by category for the given type and period.
     *
     * @return Collection<int, array{category_id: int, name: string, color: ?string, icon: ?string, total: int}>
     */
    public function byCategory(TransactionType $type, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $rows = Transaction::query()
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->where('transactions.type', $type->value)
            ->when($from, fn (Builder $query) => $query->whereDate('transactions.transaction_date', '>=', $from))
            ->when($to, fn (Builder $query) => $query->whereDate('transactions.transaction_date', '<=', $to))
            ->groupBy('transactions.category_id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderByDesc('total')
            ->get([
                'transactions.category_id',
                'categories.name',
                'categories.color',
                'categories.icon',
                DB::raw('SUM(transactions.amount) as total'),
            ]);

        return $rows->map(fn ($row) => [
            'category_id' => (int) $row->category_id,
            'name' => $row->name,
            'color' => $row->color,
            'icon' => $row->icon,
            'total' => (int) $row->total,
        ]);
    }

    /**
     * Monthly income-vs-expense series for the last N months (oldest first).
     *
     * @return Collection<int, array{year: int, month: int, label: string, income: int, expense: int}>
     */
    public function monthlySeries(int $months = 6): Collection
    {
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);

        $buckets = Transaction::query()
            ->whereDate('transaction_date', '>=', $start->toDateString())
            ->get(['transaction_date', 'type', 'amount'])
            ->groupBy(fn (Transaction $transaction) => $transaction->transaction_date->format('Y-m'));

        $series = collect();

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonths($i);
            $bucket = $buckets->get($month->format('Y-m'), collect());

            $series->push([
                'year' => $month->year,
                'month' => $month->month,
                'label' => $month->format('M'),
                'income' => $bucket->sum(fn (Transaction $transaction) => $transaction->type === TransactionType::Income ? $transaction->amount : 0),
                'expense' => $bucket->sum(fn (Transaction $transaction) => $transaction->type === TransactionType::Expense ? $transaction->amount : 0),
            ]);
        }

        return $series;
    }

    /**
     * Sum of transaction amounts for a type within the given period.
     */
    protected function sumForType(TransactionType $type, ?Carbon $from, ?Carbon $to): int
    {
        return (int) Transaction::query()
            ->where('type', $type->value)
            ->when($from, fn (Builder $query) => $query->whereDate('transaction_date', '>=', $from))
            ->when($to, fn (Builder $query) => $query->whereDate('transaction_date', '<=', $to))
            ->sum('amount');
    }
}
