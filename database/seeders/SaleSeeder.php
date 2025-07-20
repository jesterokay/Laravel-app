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
        // Create single record with unique invoice number
        Sale::factory()->sequence(function ($sequence) {
            $latestSale = Sale::latest()->first();
            $nextId = $latestSale ? $latestSale->id + 1 : 1;
            return [
                'invoice_number' => 'INV-' . str_pad($nextId, 4, '0', STR_PAD_LEFT),
            ];
        })->create();
    }
}