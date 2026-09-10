<native:column class="w-full items-center gap-4 py-10">
    <native:column class="w-16 h-16 rounded-full bg-theme-surface-variant items-center justify-center">
        <native:icon :name="$this->icon" :size="32" class="text-theme-on-surface-variant" />
    </native:column>

    <native:text class="text-lg font-semibold text-theme-on-surface text-center">{{ $this->title }}</native:text>
    <native:text class="text-sm text-theme-on-surface-variant text-center">{{ $this->message }}</native:text>

    @if ($this->actionLabel)
        <native:button label="{{ $this->actionLabel }}" variant="primary" @tap="openAction" />
    @endif
</native:column>
