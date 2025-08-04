<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as SpatieRole;

class UserSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('production')) {
            return; // Skip seeding in production
        }

        if (User::count() === 0) {
            $users = User::factory()->count(10)->create();
            $staffRole = SpatieRole::where('name', 'staff')->first();
            $internRole = SpatieRole::where('name', 'intern')->first();

            foreach ($users as $index => $user) {
                $role = $index % 2 === 0 && $staffRole ? 'staff' : 'intern';
                if ($role === 'staff' && $staffRole) {
                    $user->assignRole('staff');
                } elseif ($role === 'intern' && $internRole) {
                    $user->assignRole('intern');
                }
            }
        }
    }
}