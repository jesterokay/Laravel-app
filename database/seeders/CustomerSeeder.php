<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('production')) {
            return; // Skip seeding in production
        }

        Customer::factory()->count(1)->create();
    }
}