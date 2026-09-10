<?php

use App\Models\Currency;
use Database\Seeders\CurrencySeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        (new CurrencySeeder)->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $seeder = new CurrencySeeder;

        foreach ($seeder->defaults() as $attributes) {
            Currency::where('code', $attributes['code'])->delete();
        }
    }
};
