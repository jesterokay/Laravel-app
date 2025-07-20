<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        Position::firstOrCreate(['name' => 'Manager'], ['description' => 'Manages teams']);
        Position::firstOrCreate(['name' => 'Developer'], ['description' => 'Develops software']);
    }
}