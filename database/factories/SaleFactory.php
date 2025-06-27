<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'user_id' => Employee::factory(),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'tax_amount' => $this->faker->randomFloat(2, 0, 1000),
            'status' => $this->faker->randomElement(['completed', 'pending', 'cancelled']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}