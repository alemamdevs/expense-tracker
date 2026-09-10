@use('App\Support\Money')

<native:column class="w-full glass rounded-3xl p-6 gap-4 border border-theme-outline">
    <native:text class="text-sm text-theme-on-surface-variant">Total Balance</native:text>
    <native:text class="text-3xl font-bold text-theme-on-surface">{{ Money::format($balance) }}</native:text>

    <native:divider class="border-theme-outline-variant" />

    <native:row class="w-full justify-between">
        <native:column class="gap-1">
            <native:text class="text-sm text-theme-on-surface-variant">Income</native:text>
            <native:text class="text-base font-semibold text-theme-success">{{ Money::format($income) }}</native:text>
        </native:column>

        <native:column class="gap-1 items-end">
            <native:text class="text-sm text-theme-on-surface-variant">Expense</native:text>
            <native:text class="text-base font-semibold text-theme-destructive">{{ Money::format($expense) }}</native:text>
        </native:column>
    </native:row>
</native:column>
