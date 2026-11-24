<?php

namespace Tests\Feature;

use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestingSupport\UserHelper;

class BranchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

     public function test_general_receive_branch():void
    {
        UserHelper::accessUser();
        $response =$this->getJson('/api/branches');
        $response->assertForbidden();

    }

    public function test_general_create_branch():void
    {
        UserHelper::accessUser();
        $response =$this->postJson('/api/branches',[
            'name'=>'BKK',
            'address'=>'some where',
            'phone'=>'1234567890',
            'manager_id'=>null,
        ]);
        $response->assertForbidden();
        
    }

    public function test_general_receive_branch_with_id():void
    {
        UserHelper::accessUser();
        $branch=Branch::factory()->create();
        $response =$this->getJson("/api/branches/$branch->id");
        $response->assertForbidden();
    }

    public function test_general_update_branch():void
    {
        UserHelper::accessUser();
         $branch=Branch::factory()->create();
         $response =$this->putJson("/api/branches/$branch->id",[
            'name'=>"update branch",
            'address'=>'update address',
            'phone'=>'12234'
         ]);
        $response->assertForbidden();
    }

    public function test_general_delete_branch():void
    {
        UserHelper::accessUser();
        $branch =Branch::factory()->create();
        $response = $this->deleteJson("/api/branches/$branch->id");
        $response->assertForbidden();
    }

    public function test_admin_can_receive_branch():void
    {
        UserHelper::accessAdmin();
        $response =$this->getJson('/api/branches');
        $response->assertOk();

    }

    public function test_admin_can_create_branch():void
    {
        UserHelper::accessAdmin();
        $response =$this->postJson('/api/branches',[
            'name'=>'BKK',
            'address'=>'some where',
            'phone'=>'1234567890',
            'manager_id'=>null,
        ]);
        $response->assertCreated();
        $branch=Branch::where('name','BKK')->firstOrFail();
        $this->assertSame(
            'BR-' . str_pad($branch->id,4,0,STR_PAD_LEFT),$branch->code
        );
    }

    public function test_admin_can_receive_branch_with_id():void
    {
        UserHelper::accessAdmin();
        $branch=Branch::factory()->create();
        $response =$this->getJson("/api/branches/$branch->id");
        $response->assertOk();
    }

    public function test_admin_can_update_branch():void
    {
        UserHelper::accessAdmin();
         $branch=Branch::factory()->create();
         $response =$this->putJson("/api/branches/$branch->id",[
            'name'=>"update branch",
            'address'=>'update address',
            'phone'=>'12234'
         ]);
        $response->assertOk();
    }

    public function test_admin_can_delete_branch():void
    {
        UserHelper::accessAdmin();
        $branch =Branch::factory()->create();
        $response = $this->deleteJson("/api/branches/$branch->id");
        $response->assertOk();
        $this->assertSoftDeleted('branches',
           [ 'id'=>$branch->id]
        );
    }

    public function test_no_login_cannot_recieve_branch():void
    {
        $response = $this->getJson('/api/branches');
        $response->assertUnauthorized();
    }

    public function test_not_login_cannot_create_branch():void
    {
        $response =$this->postJson('/api/branches',[
            'name'=>'BKK',
            'address'=>'some where',
            'phone'=>'1234567890',
            'manager_id'=>null,
        ]);
        $response->assertUnauthorized();
    }

    public function test_no_login_cannot_recieve_branch_with_id():void
    {
        $branch =Branch::factory()->create();
        $response =$this->getJson("/api/branches/$branch->id");
        $response->assertUnauthorized();
    }

    public function test_not_login_cannot_update_branch():void
    {
        $branch =Branch::factory()->create();
        $response = $this->putJson("/api/branches/$branch->id",[
            'name'=>"update",
            'address'=>"update",
            'phone'=>'12345'
        ]);
        $response->assertUnauthorized();
    }

    public function test_no_login_cannot_delete_branch():void
    {
        $branch=Branch::factory()->create();
        $response =$this->deleteJson("/api/branches/$branch->id");
        $response->assertUnauthorized();
    }
}
