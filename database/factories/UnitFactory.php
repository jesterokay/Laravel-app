<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition()
    {
        $name = $this->faker->word;
        return [
            'name' => $name,
            'short_name' => strtoupper(substr($name, 0, 3)),
        ];
    }
}