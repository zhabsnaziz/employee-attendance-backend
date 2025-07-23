<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $employee = Employee::factory()->create();

        return [
            'employee_id' => $employee->employee_id,
            'attendance_id' => 'ATD-' . Str::uuid(),
            'clock_in' => now()->subHours(8),
            'clock_out' => now(),
        ];
    }
}
