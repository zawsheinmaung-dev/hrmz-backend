<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use Database\Factories\ShiftAssignmentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShiftAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_recieve_all_shift_assignment(): void
    {
        ShiftAssignment::factory()->create();
        $response = $this->getJson('/api/shiftassignment');
        $response->assertOk()->assertJsonCount(1, 'shiftAssignments');
    }

    public function test_create_shift_assignment(): void
    {
        $emp = Employee::factory()->create();
        $shift = Shift::factory()->create();
        $response = $this->postJson('/api/shiftassignment', [
            'employee_id' => $emp->id,
            'shift_id' => $shift->id,
            'effective_date' => today()
        ]);

        $response->assertCreated();
    }

    public function test_shift_assignment_recieve_with_id(): void
    {
        $shiftAssign = ShiftAssignment::factory()->create();
        $response = $this->getJson("/api/shiftassignment/$shiftAssign->id");
        $response->assertOk();
    }

    public function test_delete_shift_assignment(): void
    {
        $shiftAssign = ShiftAssignment::factory()->create();
        $response = $this->deleteJson("/api/shiftassignment/$shiftAssign->id");
        $response->assertOk();
        $this->assertSoftDeleted('shift_assignments',[
            'id'=>$shiftAssign->id
        ]);
    }
}
