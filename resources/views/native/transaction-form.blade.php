<native:top-bar title="{{ $this->title }}" show-navigation-icon="true" />

<native:scroll-view class="h-full w-full bg-theme-background">
    <native:column class="w-full gap-6 p-5 pb-10">

        <native:column class="gap-2">
            <native:text class="text-sm font-medium text-theme-on-surface-variant">Type</native:text>
            <native:button-group :options="$this->typeOptions" native:model="typeIndex" />
        </native:column>

        <native:column class="gap-2">
            <native:amount-input :minor-units="$this->amount" @change="amountChanged" />
            @if ($this->errorFor('amount'))
                <native:text class="text-sm text-theme-destructive">{{ $this->errorFor('amount') }}</native:text>
            @endif
        </native:column>

        <native:column class="gap-2">
            <native:text class="text-sm font-medium text-theme-on-surface-variant">Category</native:text>
            <native:category-selector :type="$this->type" :selected-category-id="$this->categoryId" @select="categorySelected" />
            @if ($this->errorFor('category_id'))
                <native:text class="text-sm text-theme-destructive">{{ $this->errorFor('category_id') }}</native:text>
            @endif
        </native:column>

        <native:column class="gap-2">
            <native:date-picker mode="date" label="Date" native:model="date" />
            @if ($this->errorFor('transaction_date'))
                <native:text class="text-sm text-theme-destructive">{{ $this->errorFor('transaction_date') }}</native:text>
            @endif
        </native:column>

        <native:column class="gap-2">
            <native:filled-text-input native:model="note" label="Note" placeholder="Optional" multiline min-lines="2" />
        </native:column>

        <native:button
            class="w-full"
            label="{{ $this->isEditing() ? 'Save Changes' : 'Add Transaction' }}"
            variant="primary"
            @tap="save"
        />

    </native:column>
</native:scroll-view>
