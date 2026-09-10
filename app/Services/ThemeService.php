<?php

namespace App\Services;

use App\Models\Setting;
use InvalidArgumentException;
use Native\Mobile\UI\Theme;

class ThemeService
{
    /**
     * The settings key that stores the selected accent.
     */
    public const SETTINGS_KEY = 'theme_accent';

    /**
     * The default accent (Blue) — matches the palette in `config/native-ui.php`.
     */
    public const DEFAULT_ACCENT = 'blue';

    /**
     * The 7 accent presets, each with `primary` / `on-primary` for light and dark.
     *
     * @return array<string, array{label: string, light: array{primary: string, 'on-primary': string}, dark: array{primary: string, 'on-primary': string}}>
     */
    public function presets(): array
    {
        return [
            'blue' => [
                'label' => 'Blue',
                'light' => ['primary' => '#2563EB', 'on-primary' => '#FFFFFF'],
                'dark' => ['primary' => '#3B82F6', 'on-primary' => '#FFFFFF'],
            ],
            'purple' => [
                'label' => 'Purple',
                'light' => ['primary' => '#7C3AED', 'on-primary' => '#FFFFFF'],
                'dark' => ['primary' => '#8B5CF6', 'on-primary' => '#FFFFFF'],
            ],
            'pink' => [
                'label' => 'Pink',
                'light' => ['primary' => '#DB2777', 'on-primary' => '#FFFFFF'],
                'dark' => ['primary' => '#EC4899', 'on-primary' => '#FFFFFF'],
            ],
            'red' => [
                'label' => 'Red',
                'light' => ['primary' => '#DC2626', 'on-primary' => '#FFFFFF'],
                'dark' => ['primary' => '#EF4444', 'on-primary' => '#FFFFFF'],
            ],
            'orange' => [
                'label' => 'Orange',
                'light' => ['primary' => '#EA580C', 'on-primary' => '#FFFFFF'],
                'dark' => ['primary' => '#F97316', 'on-primary' => '#FFFFFF'],
            ],
            'green' => [
                'label' => 'Green',
                'light' => ['primary' => '#16A34A', 'on-primary' => '#FFFFFF'],
                'dark' => ['primary' => '#22C55E', 'on-primary' => '#FFFFFF'],
            ],
            'teal' => [
                'label' => 'Teal',
                'light' => ['primary' => '#0D9488', 'on-primary' => '#FFFFFF'],
                'dark' => ['primary' => '#14B8A6', 'on-primary' => '#FFFFFF'],
            ],
        ];
    }

    /**
     * Resolve a preset by key.
     *
     * @return array{label: string, light: array{primary: string, 'on-primary': string}, dark: array{primary: string, 'on-primary': string}}
     */
    public function preset(string $key): array
    {
        return $this->presets()[$key] ?? throw new InvalidArgumentException("Unknown theme preset [{$key}].");
    }

    /**
     * Apply an accent at runtime and persist the selection.
     */
    public function apply(string $key, bool $persist = true): void
    {
        $preset = $this->preset($key);

        Theme::merge([
            'light' => $preset['light'],
            'dark' => $preset['dark'],
        ]);

        if ($persist) {
            Setting::setValue(static::SETTINGS_KEY, $key);
        }
    }

    /**
     * Restore the default accent and remove any stored override.
     */
    public function reset(): void
    {
        $this->apply(static::DEFAULT_ACCENT, persist: false);

        Setting::forget(static::SETTINGS_KEY);
    }

    /**
     * The currently selected accent key (defaults to Blue).
     */
    public function current(): string
    {
        $key = Setting::getValue(static::SETTINGS_KEY, static::DEFAULT_ACCENT);

        return array_key_exists($key, $this->presets()) ? $key : static::DEFAULT_ACCENT;
    }
}
