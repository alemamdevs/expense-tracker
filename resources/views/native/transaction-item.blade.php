<native:row class="w-full items-center gap-3">
    <native:column class="w-11 h-11 rounded-full bg-theme-primary/15 items-center justify-center">
        <native:icon :name="$this->categoryIcon" :size="22" class="text-theme-primary" />
    </native:column>

    <native:column class="flex-1 gap-0.5">
        <native:text class="text-base font-medium text-theme-on-surface">{{ $this->categoryName }}</native:text>
        @if ($this->note)
            <native:text class="text-sm text-theme-on-surface-variant">{{ $this->note }}</native:text>
        @endif
    </native:column>

    <native:column class="items-end gap-0.5">
        <native:text class="text-base font-semibold {{ $this->amountColor }}">{{ $this->amountText }}</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">{{ $this->dateText }}</native:text>
    </native:column>
</native:row>
