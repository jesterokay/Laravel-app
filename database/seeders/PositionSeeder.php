<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        Position::create(['name' => 'Manager', 'description' => 'Manages teams']);
        Position::create(['name' => 'Developer', 'description' => 'Develops software']);
    }
}