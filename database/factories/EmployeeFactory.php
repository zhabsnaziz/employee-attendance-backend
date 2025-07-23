<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'employee_id'    => 'EMP' . $this->faker->unique()->numberBetween(1000, 9999),
            'name'           => $this->faker->name(),
            'address'        => $this->faker->address(),
            'departement_id' => Department::factory(),
        ];
    }
}
