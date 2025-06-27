<?php

namespace Database\Factories;

use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRateFactory extends Factory
{
    protected $model = TaxRate::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word . ' Tax',
            'rate' => $this->faker->randomFloat(2, 0, 20),
        ];
    }
}