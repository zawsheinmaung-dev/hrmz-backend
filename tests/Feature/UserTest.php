<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_admin_can_recieve_all_users(): void
    {
        $role=Role::create([
            'name'=>'admin',
            'guard_name'=>'web'
        ]);
        $admin=User::factory()->create();
        $admin->assignRole($role);
        Sanctum::actingAs($admin);
        $response =$this->getJson('/api/users');
        $response->assertOk();
    }

    public function test_no_admin_cannot_recieve_all_user():void
    {
        $role=Role::create([
            'name'=>'user',
            'guard_name'=>'web'
        ]);
        $user =User::factory()->create();
        $user->assignRole($role);
        Sanctum::actingAs($user);
        $response =$this->getJson('/api/users');
        $response->assertForbidden();
    }

    public function test_no_login_user_cannot_recieve_all_user(): void
    {
        $response =$this->getJson('/api/users');
        $response->assertUnauthorized();
    }

    public function test_admin_can_create_new_user():void
    {
        $role=Role::create([
            'name'=>'admin',
            'guard_name'=>'web'
        ]);

        $userRole = Role::where('name','admin')->first();
        $admin=User::factory()->create();
        $admin->assignRole($role);
        Sanctum::actingAs($admin);
        $response =$this->postJson('/api/users',[
            'name'=>'admin test',
            'email'=>'admintest@gmail.com',
            'password'=>'Password1!',
            'role_id'=>$userRole->id
        ]);
        $response->assertCreated();
    }

    public function test_no_admin_cannot_create_new_user():void
    {
        $role = Role::create([
            'name'=>'user',
            'guard_name'=>'web'
        ]);
        $user =User::factory()->create();
        $user->assignRole($role);
        Sanctum::actingAs($user);
        $response =$this->postJson('/api/users',[
            'name'=>'user test',
            'email'=>'usertest@gmail.com',
            'password'=>'Password1!',
            'role_id'=>$role->id
        ]);

        $response->assertForbidden();
    }

    public function test_not_login_user_cannot_create_new_user():void
    {
        $response =$this->postJson('/api/users',[
            'name'=>'user',
            'email'=>'user@gmail.com',
            'password'=>'Password1!',
            'role_id'=>1
        ]);

        $response->assertUnauthorized();
    }

    public function test_admin_can_update_user():void
    {
        $role=Role::create([
            'name'=>'admin',
            'guard_name'=>'web'
        ]);

        $admin=User::factory()->create();
        $admin->assignRole($role);
        Sanctum::actingAs($admin);

        $user =User::factory()->create();

        $response =$this->putJson("/api/users/{$user->id}",[
            'name'=>'update test',
            'email'=>'updateemail@gmail.com',
            'role_id'=>$role->id
        ]);

        $response->assertOk();

    }

    public function test_no_admin_cannot_update_user():void
    {
        $role=Role::create([
            'name'=>'user',
            'guard_name'=>'web'
        ]);

        $user=User::factory()->create();
        $user->assignRole($role);
        Sanctum::actingAs($user);

        $userTest =User::factory()->create();

        $response =$this->putJson("/api/users/{$user->id}",[
            'name'=>'update test',
            'email'=>'updateemail@gmail.com',
            'role_id'=>$role->id
        ]);

        $response->assertForbidden();
    }

    public function test_no_login_user_cannot_update_user():void
    {
        $user = User::factory()->create();
        $response =$this->putJson("/api/users/{$user->id}",[
            'name'=>'user',
            'email'=>'user@email.com',
        ]);
        $response->assertUnauthorized();
    }

    
}
