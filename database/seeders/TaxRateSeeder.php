<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxRate;
use App\Models\Business;

class TaxRateSeeder extends Seeder
{
    public function run()
    {

        $taxRates = [
            ['rate' => 5, 'name' => '5% Tax'],
            ['rate' => 10, 'name' => '10% Tax'],
            ['rate' => 15, 'name' => '15% Tax'],
        ];

        foreach ($taxRates as $tax) {
            TaxRate::firstOrCreate(
                ['rate' => $tax['rate']],
                [
                    'name' => $tax['name'],
                ]
            );
        }
    }
}
