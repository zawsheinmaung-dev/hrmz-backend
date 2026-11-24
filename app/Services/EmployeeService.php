<?php

namespace App\Services;

use App\Helpers\CodeGenerate;
use App\Helpers\CodeGenerator;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeService
{
    public function __construct(protected UserService $userService) {}

    public function searchEmployee($name)
    {
        $employees = Employee::where('first_name', 'LIKE', "%{$name}%")->orWhere('last_name', 'LIKE', "%{$name}%")->orWhere('employee_code', 'LIKE', "%{$name}%")->orWhere('personal_email', 'LIKE', "%{$name}%")->limit(5)->get();
        return $employees;
    }

    public function storeEmployee($data)
    {
        DB::transaction(function () use ($data) {
            $user = $this->userService->createUser([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $this->generateEmail($data['first_name'], $data['last_name']),
                'password' => Str::random(12)
            ]);

            $employee = Employee::create($data);
            $employee->update([
                'user_id' => $user->id,
                'employee_code' => CodeGenerate::generate('EMP-', $employee->id, 4)
            ]);
        });
    }

    public function updateEmployee($id, $data)
    {
        $employee = Employee::findOrFail($id);
        $employee->update(Arr::only($data, [
            'first_name',
            'last_name',
            'gender',
            'dob',
            'national_id',
            'phone',
            'personal_email',
            'address',
            'branch_id',
            'department_id',
            'position_id',
            'manager_id',
            'join_date',
            'resign_date',
            'employment_type',
            'employment_status',
            'profile_photo',

        ]));
        return $employee->load('branch', 'department', 'position', 'manager');
    }

    public function deleteEmployee($id)
    {
        DB::transaction(function () use ($id) {
            $employee = Employee::with('user')->findOrFail($id);

            if ($employee->user) {
               
                $employee->user->delete();
            }
            $employee->delete();
        });
    }

    private function generateEmail($first_name, $last_name)
    {
        $base = strtolower(str_replace('', ' ', trim($first_name) . trim($last_name)));
        $email = $base . "@company.com";
        $count = 0;
        while (User::where('email', $email)->exists()) {
            $email = $base . $count . "@company.com";
            $count++;
        }
        return $email;
    }
}
