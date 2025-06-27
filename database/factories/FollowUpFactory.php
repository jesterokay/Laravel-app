<?php

namespace Database\Factories;

use App\Models\FollowUp;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowUpFactory extends Factory
{
    protected $model = FollowUp::class;

    public function definition()
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'notes' => $this->faker->sentence,
            'follow_up_date' => $this->faker->date,
            'status' => $this->faker->randomElement(['pending', 'completed']),
        ];
    }
}