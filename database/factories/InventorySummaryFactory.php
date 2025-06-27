<?php

namespace Database\Factories;

use App\Models\InventorySummary;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventorySummaryFactory extends Factory
{
    protected $model = InventorySummary::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'last_updated' => $this->faker->dateTimeThisYear(),
        ];
    }
}