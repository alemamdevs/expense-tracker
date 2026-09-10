<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    /**
     * Retrieve a stored setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Create or update a stored setting value by key.
     */
    public static function setValue(string $key, mixed $value): static
    {
        return static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    /**
     * Remove a stored setting by key.
     */
    public static function forget(string $key): void
    {
        static::where('key', $key)->delete();
    }
}
