<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as SpatieRole;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('production')) {
            return; // Skip seeding in production
        }

        if (Employee::count() === 0) {
            $employees = Employee::factory()->count(10)->create();
            $staffRole = SpatieRole::where('name', 'staff')->first();
            $internRole = SpatieRole::where('name', 'intern')->first();

            foreach ($employees as $index => $employee) {
                $role = $index % 2 === 0 && $staffRole ? 'staff' : 'intern';
                if ($role === 'staff' && $staffRole) {
                    $employee->assignRole('staff');
                } elseif ($role === 'intern' && $internRole) {
                    $employee->assignRole('intern');
                }
            }
        }
    }
}