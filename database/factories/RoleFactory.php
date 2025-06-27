<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->jobTitle, // unique name
            'guard_name' => 'web', // assuming the guard name is fixed, or you can use a unique faker value
            'updated_at' => now(),
            'created_at' => now(),
        ];
    }
}
