<native:scroll-view class="w-full h-full bg-theme-background">
    <native:column class="w-full gap-6 p-5">

        {{-- Header: greeting + current month --}}
        <native:column class="gap-1">
            <native:text class="text-sm text-theme-on-surface-variant">{{ $this->greeting }}</native:text>
            <native:text class="text-2xl font-bold text-theme-on-surface">{{ $this->currentMonth }}</native:text>
        </native:column>

        {{-- Balance summary --}}
        <native:balance-card
            :balance="$this->summary['balance']"
            :income="$this->summary['income']"
            :expense="$this->summary['expense']"
        />

        {{-- Recent transactions --}}
        <native:column class="gap-4">
            <native:row class="w-full items-center justify-between">
                <native:text class="text-lg font-semibold text-theme-on-surface">Recent Transactions</native:text>

                @if ($this->hasTransactions)
                    <native:pressable press-opacity="0.7" @navigate="/transactions">
                        <native:text class="text-sm font-medium text-theme-primary">See All</native:text>
                    </native:pressable>
                @endif
            </native:row>

            @if ($this->hasTransactions)
                <native:column class="w-full rounded-3xl border border-theme-outline-variant bg-theme-surface">
                    @foreach ($this->recentTransactions as $transaction)
                        <native:column class="w-full px-4 py-3">
                            <native:transaction-item :transaction="$transaction" key="transaction-{{ $transaction->id }}" />
                        </native:column>

                        @if (! $loop->last)
                            <native:divider class="border-theme-outline-variant" />
                        @endif
                    @endforeach
                </native:column>
            @else
                <native:empty-state
                    title="No transactions yet"
                    message="Start tracking your expenses and take control of your finances."
                    icon="receipt"
                    action-label="Add Transaction"
                    :action-url="'/transactions/add'"
                />
            @endif
        </native:column>

    </native:column>
</native:scroll-view>
