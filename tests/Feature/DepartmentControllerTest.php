<?php

namespace Tests\Feature;

use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_department()
    {
        $response = $this->postJson('/api/departments', [
            'department_name'    => 'QA',
            'max_clock_in_time'  => '08:00:00',
            'max_clock_out_time' => '17:00:00',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['department_name' => 'QA']);
    }

    public function test_list_departments()
    {
        Department::factory()->count(3)->create();

        $response = $this->getJson('/api/departments');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_show_department()
    {
        $dept = Department::factory()->create();

        $response = $this->getJson("/api/departments/{$dept->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['department_name' => $dept->department_name]);
    }

    public function test_update_department()
    {
        $dept = Department::factory()->create();

        $response = $this->putJson("/api/departments/{$dept->id}", [
            'department_name'    => 'Updated QA',
            'max_clock_in_time'  => '09:00:00',
            'max_clock_out_time' => '18:00:00',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['department_name' => 'Updated QA']);
    }

    public function test_delete_department()
    {
        $dept = Department::factory()->create();

        $response = $this->deleteJson("/api/departments/{$dept->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Department deleted successfully']);
    }
}
