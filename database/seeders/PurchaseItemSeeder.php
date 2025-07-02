<?php

namespace Database\Seeders;

use App\Models\PurchaseItem;
use Illuminate\Database\Seeder;

class PurchaseItemSeeder extends Seeder
{
    public function run()
    {
        PurchaseItem::factory()->count(1)->create();
    }
}