<?php

namespace Database\Seeders;

use App\Models\SalesSummary;
use App\Models\BusinessLocation;
use Illuminate\Database\Seeder;

class SalesSummarySeeder extends Seeder
{
    public function run()
    {
        SalesSummary::factory()->count(10)->create();
    }
}