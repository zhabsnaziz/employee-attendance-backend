<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'department_name'    => $this->faker->unique()->company(),
            'max_clock_in_time'  => $this->faker->randomElement(['08:00:00', '08:30:00', '09:00:00']),
            'max_clock_out_time' => $this->faker->randomElement(['17:00:00', '17:30:00', '18:00:00']),
        ];
    }
}
