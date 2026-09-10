<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class EmptyState extends NativeComponent
{
    public string $title = '';

    public string $message = '';

    public string $icon = 'receipt';

    public ?string $actionLabel = null;

    public ?string $actionUrl = null;

    public function openAction(): void
    {
        if ($this->actionUrl !== null && $this->actionUrl !== '') {
            $this->navigate($this->actionUrl);
        }
    }

    public function render(): View
    {
        return view('native.empty-state');
    }
}
