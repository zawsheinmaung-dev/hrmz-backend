<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PositionTest extends TestCase
{
    use RefreshDatabase;

    public function test_recieve_all_position(): void
    {
        Position::factory()->create();
        $response = $this->getJson('/api/positions');
        $response->assertOk()->assertJsonCount(1, 'positions');
    }

    public function test_recive_position_with_id(): void
    {
        $pos = Position::factory()->create();
        $response = $this->getJson("/api/positions/$pos->id");
        $response->assertOk();
    }

    public function test_create_position(): void
    {
        $dep = Department::factory()->create();
        $response = $this->postJson("/api/positions/", [
            'department_id' => $dep->id,
            'title' => 'Update',
            'level' => 'junior'
        ]);

        $response->assertCreated();
    }

    public function test_update_position():void
    {
        $dep =Department::factory()->create();
        $pos =Position::factory()->create();
        $response =$this->putJson("/api/positions/$pos->id",[
            'department_id'=>$dep->id,
            'title'=>'update',
            'level'=>$pos->level
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('positions',[
            'id'=>$pos->id,
            'title'=>'update'
        ]);
    }

    public function test_delete_position():void
    {
        $pos =Position::factory()->create();
        $response =$this->deleteJson("/api/positions/$pos->id");
        $response->assertOk();
        $this->assertSoftDeleted('positions',[
            'id'=>$pos->id
        ]);
    }
    
}
