<?php

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        (new CategorySeeder)->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $seeder = new CategorySeeder;

        foreach ($seeder->defaults() as $attributes) {
            Category::where('name', $attributes['name'])
                ->where('type', $attributes['type'])
                ->delete();
        }
    }
};
