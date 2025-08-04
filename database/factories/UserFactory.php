<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'hire_date' => $this->faker->date(),
            'salary' => $this->faker->numberBetween(30000, 100000),
            'status' => $this->faker->randomElement(['active', 'inactive', 'terminated']),
            'image' => null, // Prevent hardcoded paths
        ];
    }
}