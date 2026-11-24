<?php

namespace Tests\TestingSupport;


use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

class UserHelper
{
    public static function accessAdmin()
    {
        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        $admin = User::factory()->create();
        $admin->assignRole($role);
        Sanctum::actingAs($admin);
        return $admin;
    }

    public static function accessUser()
    {
        $role = Role::create([
            'name' => 'user',
            'guard_name' => 'web'
        ]);
        $user = User::factory()->create();
        $user->assignRole($role);
        Sanctum::actingAs($user);

        return $user;
    }
}
