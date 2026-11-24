<?php

namespace Tests\Feature;

use App\Models\Shift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShiftTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_recieve_all_shift():void
    {
        Shift::factory()->create();
        $response = $this->getJson('/api/shifts');
        $response->assertOk()->assertJsonCount(1,'shifts');
    }

    public function test_create_shift():void
    {
        $response = $this->postJson('/api/shifts',[
            'name'=>'shift',
            'start_time'=>'09:00:00',
            'end_time'=>'17:00:00'
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('shifts',[
           'name'=>'shift',
        ]);
    }

    public function test_recivev_shift_with_id():void
    {
        $shift = Shift::factory()->create();
        $response = $this->getJson("/api/shifts/$shift->id");
        $response->assertOk();
    }

    public function test_update_shift():void
    {
        $shift =Shift::factory()->create();
        $response =$this->putJson("/api/shifts/$shift->id",[
            'name'=>"shift update",
            'start_time'=>$shift->start_time,
            'end_time'=>$shift->end_time,
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('shifts',[
            'id'=>$shift->id
        ]);
    }

    public function test_shift_delete():void
    {
        $shift = Shift::factory()->create();
        $response = $this->deleteJson("/api/shifts/$shift->id");
        $response->assertOk();
        $this->assertSoftDeleted('shifts',[
            'id'=>$shift->id
        ]);
    }
}
