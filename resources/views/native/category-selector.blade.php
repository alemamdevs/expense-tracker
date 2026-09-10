@use('App\Icons\Ios')
@use('App\Icons\Android')

<native:pressable
    class="w-full flex-row items-center justify-between rounded-2xl border border-theme-outline bg-theme-surface px-4 py-3"
    press-opacity="0.8"
    @tap="open"
>
    <native:row class="items-center gap-3">
        @if ($this->selectedCategory)
            <native:column class="h-10 w-10 items-center justify-center rounded-full bg-theme-primary/15">
                <native:icon :name="$this->selectedCategory->icon" :size="20" class="text-theme-primary" />
            </native:column>
            <native:text class="text-base text-theme-on-surface">{{ $this->selectedCategory->name }}</native:text>
        @else
            <native:text class="text-base text-theme-on-surface-variant">Select category</native:text>
        @endif
    </native:row>

    <native:icon :ios="Ios::ChevronDown" :android="Android::ExpandMore" :size="20" class="text-theme-on-surface-variant" />
</native:pressable>

<native:bottom-sheet :visible="$this->visible" detents="medium" @dismiss="dismiss">
    <native:column class="w-full gap-1 px-4 pb-8 pt-2">
        <native:text class="px-2 py-3 text-lg font-semibold text-theme-on-surface">Select Category</native:text>

        @foreach ($this->categories as $category)
            <native:pressable
                class="w-full flex-row items-center justify-between rounded-xl px-3 py-2.5"
                press-opacity="0.7"
                @tap="select({{ $category->id }})"
            >
                <native:row class="items-center gap-3">
                    <native:column class="h-10 w-10 items-center justify-center rounded-full bg-theme-primary/15">
                        <native:icon :name="$category->icon" :size="20" class="text-theme-primary" />
                    </native:column>
                    <native:text class="text-base text-theme-on-surface">{{ $category->name }}</native:text>
                </native:row>

                @if ($this->selectedCategoryId === $category->id)
                    <native:icon :ios="Ios::Checkmark" :android="Android::Check" :size="20" class="text-theme-primary" />
                @endif
            </native:pressable>
        @endforeach
    </native:column>
</native:bottom-sheet>
