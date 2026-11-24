<?php

namespace App\Services;

use App\Helpers\CodeGenerate;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentService
{
    public function searchDepartment($name)
    {
        $department =Department::where('name','LIKE',"%{$name}%")->orWhere('code','LIKE',"%{$name}%")->limit(5)->get();
        return $department;
    }

    public function depStore($data)
    {
        DB::transaction(function () use ($data) {
            $dep = Department::create([
                'branch_id' => $data['branch_id'],
                'name' => $data['name'],
                'head_department_id' => $data['head_department_id'] ?? null,
                'head_emp_id' => $data['head_emp_id'] ?? null,
                'description' => $data['description'] ?? null,
            ]);
            $dep->update([
                'code' => CodeGenerate::generate('DEP-', $dep->id, 3)
            ]);
        });
    }

    public function depUpdate($id, $data)
    {
        $dep = Department::findOrFail($id);
        $dep->update([
            'branch_id' => $data['branch_id'],
            'name' => $data['name'],
            'head_department_id' => $data['head_department_id'] ?? null,
            'head_emp_id' => $data['head_emp_id'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
        return $dep->fresh();
    }

    
}
