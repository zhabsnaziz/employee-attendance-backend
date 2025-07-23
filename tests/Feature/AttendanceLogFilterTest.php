<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceLogFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_logs_by_date_and_department_and_check_punctuality()
    {
        // Arrange: buat 2 departemen
        $it = Department::factory()->create([
            'department_name'    => 'IT',
            'max_clock_in_time'  => '08:00:00',
            'max_clock_out_time' => '17:00:00',
        ]);

        $hr = Department::factory()->create([
            'department_name'    => 'HR',
            'max_clock_in_time'  => '09:00:00',
            'max_clock_out_time' => '18:00:00',
        ]);

        // Buat employee untuk masing-masing departemen
        $empIt = Employee::factory()->create([
            'departement_id' => $it->id,
            'employee_id'    => 'EMP001',
        ]);

        $empHr = Employee::factory()->create([
            'departement_id' => $hr->id,
            'employee_id'    => 'EMP002',
        ]);

        // Buat absensi IT - telat masuk, tepat waktu pulang
        Attendance::create([
            'employee_id'   => $empIt->employee_id,
            'attendance_id' => 'ATD-IT-001',
            'clock_in'      => now()->setTime(8, 30),
            'clock_out'     => now()->setTime(17, 0),
        ]);

        // Buat absensi HR - tepat waktu masuk, telat pulang
        Attendance::create([
            'employee_id'   => $empHr->employee_id,
            'attendance_id' => 'ATD-HR-001',
            'clock_in'      => now()->setTime(8, 50),
            'clock_out'     => now()->setTime(18, 30),
        ]);

        // Act: request filter log untuk departemen IT
        $response = $this->getJson('/api/attendance/logs?start_date=' . now()->format('Y-m-d') .
            '&end_date=' . now()->format('Y-m-d') .
            '&department_id=' . $it->id);

        // Assert: hanya data departemen IT ditampilkan
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'employee_id' => 'EMP001',
            'on_time_in'  => false,
            'on_time_out' => true,
        ]);
    }
}
