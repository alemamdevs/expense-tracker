<?php

use App\NativeComponents\AmountInput;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

it('renders the amount label and a currency-aware placeholder', function () {
    Native::test(AmountInput::class)
        ->assertSee('Amount')
        ->assertSee('0.00');
});

it('passes the active currency symbol as the input prefix', function () {
    $prefix = Native::test(AmountInput::class)
        ->tree();

    expect(amountInputTreeProp($prefix, 'filled_text_input', 'prefix'))->toBe('৳');
});

it('starts empty when no amount is seeded', function () {
    Native::test(AmountInput::class)
        ->assertSet('amount', '');
});

it('keeps a typed major-unit amount in the field', function () {
    Native::test(AmountInput::class)
        ->set('amount', '250.50')
        ->assertSet('amount', '250.50');
});

/**
 * Recursively find the first node of the given type and return one of its props.
 */
function amountInputTreeProp(array $node, string $type, string $prop): mixed
{
    if (($node['type'] ?? null) === $type) {
        return $node['props'][$prop] ?? null;
    }

    foreach ($node['children'] ?? [] as $child) {
        $found = amountInputTreeProp($child, $type, $prop);
        if ($found !== null) {
            return $found;
        }
    }

    return null;
}
