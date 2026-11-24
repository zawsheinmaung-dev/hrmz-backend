<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $departmentService) {}

    public function search($name)
    {
        try {
            $departments =$this->departmentService->searchDepartment($name);
            return response()->json(['success'=>true,'departments'=>$departments],200);
        } catch (\Exception $th) {
            return response()->json(['success'=>false,'message'=>$th->getMessage()],500);
        }
    }

    public function index()
    {
        $dep = Department::with('branch')->withCount('employees')->get();
        return response()->json(['departments' => $dep]);
    }

    public function store(DepartmentRequest $request)
    {
        try {
            $this->departmentService->depStore($request->validated());
            return response()->json(['success' => true, 'message' => 'Deperment Create Success'], 201);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dep = Department::with('branch','departmentManager','headDepartment','employees.position')->withCount('employees')->findOrFail($id);
        return response()->json(['department' => $dep]);
    }

    public function update($id, DepartmentRequest $request)
    {
        try {
            $dep = $this->departmentService->depUpdate($id, $request->validated());
            return response()->json(['success' => true, 'message' => 'Deperment Update Success', 'department' => $dep], 200);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $dep = Department::findOrFail($id);
        $dep->delete();
        return response()->json(['success' => true, 'message' => 'Department Delete Success']);
    }

    public function removeBranch(Department $department)
    {
        try {
            $department->update([
                'branch_id'=>null
            ]);
            return response()->json(['success'=>true,'department'=>$department->fresh()],200);
        } catch (\Exception $th) {
            return response()->jsonp(['success'=>false,'message'=>$th->getMessage()],500);
        }
    }
}
