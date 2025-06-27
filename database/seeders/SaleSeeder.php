<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run()
    {
        // Use factory for 10 records with unique invoice numbers
        Sale::factory()->count(10)->sequence(function ($sequence) {
            $latestSale = Sale::latest()->first();
            $nextId = $latestSale ? $latestSale->id + $sequence->index + 1 : $sequence->index + 1;
            return [
                'invoice_number' => 'INV-' . str_pad($nextId, 4, '0', STR_PAD_LEFT),
            ];
        })->create();
    }
}