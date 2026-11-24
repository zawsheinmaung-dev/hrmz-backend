<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    // Normal CURD
    public function test_recieve_all_departments(): void
    {
        Department::factory()->create();
        $response = $this->getJson('/api/departments');
        $response->assertOk()->assertJsonCount(1,'departments');
    }

    public function test_create_department(): void
    {
        $branch = Branch::factory()->create();
        $response = $this->postJson('/api/departments', [
            'branch_id' => $branch->id,
            'name' => "TESTING",
            'description' => "TESTING",
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('departments', [
            'branch_id' => $branch->id,
            'name' => "TESTING",
            'description' => "TESTING",
        ]);
    }

    public function test_recieve_department_with_id(): void
    {
        $dep = Department::factory()->create();
        $response = $this->getJson("/api/departments/$dep->id");
        $response->assertOk()->assertJsonPath('department.id', $dep->id);
    }

    public function test_update_department(): void
    {
        $dep = Department::factory()->create();
        $branch = Branch::factory()->create();
        $response = $this->putJson("/api/departments/$dep->id", [
            'branch_id' => $branch->id,
            'name' => 'update'
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('departments', [
            'branch_id' => $branch->id,
            'name' => 'update'
        ]);
    }

    public function test_soft_delete_department(): void
    {
        $dep = Department::factory()->create();
        $response = $this->deleteJson("/api/departments/$dep->id");
        $this->assertSoftDeleted('departments', [
            'id' => $dep->id
        ]);
        $response->assertOk();
    }
}
