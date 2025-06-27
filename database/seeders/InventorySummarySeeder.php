<?php

namespace Database\Seeders;

use App\Models\InventorySummary;
use Illuminate\Database\Seeder;

class InventorySummarySeeder extends Seeder
{
    public function run()
    {
        InventorySummary::factory()->count(10)->create();
    }
}