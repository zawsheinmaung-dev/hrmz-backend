<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
       
    }
    public function index()
    {
        $users =User::all();
        return response()->json($users);
    }

    public function store(UserRequest $request)
    {
        $valiUser =$request->validated();
        $this->userService->createUser($valiUser);
        return response()->json(['success'=>'New User Create Success'],201);
    }

    public function show($id)
    {
        $user =User::findOrFail($id);
        return response()->json($user,200);
    }

    public function update($id,UserRequest $request)
    {
        $valiUser=$request->validated();
        $user =$this->userService->userUpdate($id,$valiUser);
        return response()->json($user,200);
    }

    public function destroy($id)
    {
        $this->userService->userDelete($id);
        return response()->json(['success'=>'Deleting user success'],200);
    }
}
