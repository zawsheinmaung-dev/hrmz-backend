<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function getIndex()
    {
        return User::all();
    }

    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $role = Role::where('name','user')->first();
        $user->assignRole($role);
        return $user;
    }

    public function userUpdate($id,$data)
    {
        $user =User::findOrFail($id);
        $roleId=$data['role_id'];
        unset($data['role_id']);
        $user->update($data);
        $user->syncRoles($roleId);
        return $user;
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);
        $user->tokens()->delete();
        $user->delete();
    }
}
