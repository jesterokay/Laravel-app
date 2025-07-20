<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxRate;
use App\Models\Business;

class TaxRateSeeder extends Seeder
{
    public function run()
    {

        TaxRate::firstOrCreate(
            ['rate' => 5],
            [
                'name' => '5% Tax',
            ]
        );
    }
}
