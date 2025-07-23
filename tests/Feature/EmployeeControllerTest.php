<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_employee()
    {
        $dept = Department::factory()->create();

        $response = $this->postJson('/api/employees', [
            'employee_id'    => 'EMP999',
            'name'           => 'John Doe',
            'address'        => 'Somewhere',
            'departement_id' => $dept->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['employee_id' => 'EMP999']);
    }

    public function test_list_employees()
    {
        Employee::factory()->count(2)->create();

        $response = $this->getJson('/api/employees');

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    public function test_show_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/employees/{$employee->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['employee_id' => $employee->employee_id]);
    }

    public function test_update_employee()
    {
        $employee = Employee::factory()->create();
        $dept = Department::factory()->create();

        $response = $this->putJson("/api/employees/{$employee->id}", [
            'employee_id'    => $employee->employee_id,
            'name'           => 'Updated Name',
            'address'        => 'Updated Addr',
            'departement_id' => $dept->id,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_delete_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/employees/{$employee->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Employee deleted successfully']);
    }
}
