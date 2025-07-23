<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_clock_in()
    {
        $employee = Employee::factory()->create();

        $response = $this->postJson('/api/attendance/clock-in', [
            'employee_id' => $employee->employee_id,
            'description' => 'Masuk kerja',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['message' => 'Clock-in recorded']);
    }

    public function test_employee_can_clock_out()
    {
        $employee = Employee::factory()->create();
        Attendance::factory()->create([
            'employee_id' => $employee->employee_id,
            'clock_in'    => now()->subHours(8),
            'clock_out'   => null,
        ]);

        $response = $this->putJson('/api/attendance/clock-out', [
            'employee_id' => $employee->employee_id,
            'description' => 'Pulang kerja',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Clock-out recorded']);
    }

    public function test_get_attendance_logs()
    {
        $dept = Department::factory()->create([
            'max_clock_in_time'  => '08:00:00',
            'max_clock_out_time' => '17:00:00',
        ]);
        $employee = Employee::factory()->create(['departement_id' => $dept->id]);
        Attendance::factory()->create([
            'employee_id' => $employee->employee_id,
            'clock_in'    => now()->setTime(8, 30),
            'clock_out'   => now()->setTime(17, 15),
        ]);

        $response = $this->getJson('/api/attendance/logs?start_date=' . now()->format('Y-m-d') . '&end_date=' . now()->format('Y-m-d'));

        $response->assertStatus(200)
                 ->assertJsonFragment(['on_time_in' => false, 'on_time_out' => true]);
    }
}
