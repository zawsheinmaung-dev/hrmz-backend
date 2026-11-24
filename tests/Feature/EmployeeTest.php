<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_recieve_all_employee(): void
    {
        Employee::factory()->count(5)->create();
        $response = $this->getJson('/api/employees');
        $response->assertOk()->assertJsonCount(5, 'employees');
    }

    public function test_create_employee(): void
    {
        $branch = Branch::factory()->create();
        $dep = Department::factory()->create();
        $pos = Position::factory()->create();
        $response = $this->postJson('/api/employees', [
            "first_name" => "Test",
            "last_name" => "One",
            "gender" => "male",
            "phone" => "123456789",
            "personal_email" => "email1@email",
            "branch_id" => $branch->id,
            "department_id" => $dep->id,
            "position_id" => $pos->id,
            "join_date" => "12-2-2020",
            "employment_type" => "full_time",
            "employment_status" => "active"
        ]);
        $response->assertCreated();
    }

    public function test_recieve_employee_with_id(): void
    {
        $emp = Employee::factory()->create();
        $response = $this->getJson("/api/employees/$emp->id");
        $response->assertOk();
    }

    public function test_update_employee(): void
    {
        $emp = Employee::factory()->create();
        $response = $this->putJson("/api/employees/$emp->id", [
            "first_name" => "Test",
            "last_name" => "One",
            "gender" => "male",
            "phone" => "123456789",
            "personal_email" => "email1@email",
            "branch_id" => $emp->branch_id,
            "department_id" => $emp->department_id,
            "position_id" => $emp->position_id,
            "join_date" => "12-2-2020",
            "employment_type" => "full_time",
            "employment_status" => "active"
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('employees', [
            'id' => $emp->id,
            'first_name' => 'Test'
        ]);
    }

    public function test_delete_employee():void
    {
        $employee =Employee::factory()->create();
        $response =$this->deleteJson("/api/employees/$employee->id");
        $response->assertOk();
        $this->assertSoftDeleted('employees',[
            'id'=>$employee->id
        ]);
    }
}
