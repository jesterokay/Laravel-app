<?php

namespace Database\Factories;

use App\Models\PurchaseItem;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseItemFactory extends Factory
{
    protected $model = PurchaseItem::class;

    public function definition()
    {
        return [
            'purchase_id' => Purchase::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit_price' => $this->faker->randomFloat(2, 10, 100),
            'subtotal' => $this->faker->randomFloat(2, 10, 1000),
            'tax_amount' => $this->faker->randomFloat(2, 0, 100),
            'tax_rate_id' => TaxRate::factory(),
        ];
    }
}