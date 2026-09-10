<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'symbol', 'decimal_places'])]
class Currency extends Model
{
    /**
     * The default display currency code.
     */
    public const DEFAULT_CODE = 'BDT';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'decimal_places' => 'integer',
        ];
    }

    /**
     * Resolve the default (BDT) currency, or null when not yet seeded.
     */
    public static function default(): ?self
    {
        return static::where('code', static::DEFAULT_CODE)->first();
    }
}
