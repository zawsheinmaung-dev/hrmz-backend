<?php

namespace App\Http\Controllers\Api;

use App\Enums\EmploymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService) {}

    public function searhEmployee($name)
    {
        try {
            $employees = $this->employeeService->searchEmployee($name);
            return response()->json(['success' => true, 'employees' => $employees], 200);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function index()
    {
        $employees = Employee::with('department', 'branch','manager')->get();
        return response()->json(['success' => true, 'employees' => $employees,'employmentType'=>EmploymentType::cases()]);
    }

    public function store(EmployeeRequest $request)
    {
        $valiData = $request->validated();
        try {
            $this->employeeService->storeEmployee($valiData);
            return response()->json(['success' => true, 'message' => 'Employee and User create Success'], 201);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $employee = Employee::with('branch', 'department', 'position', 'manager')->findOrFail($id);
        return response()->json(['success' => true, 'employee' => $employee,'employmentType'=>EmploymentType::cases()], 200);
    }

    public function update($id, EmployeeRequest $request)
    {
        try {
            $valiData = $request->validated();
            $employee = $this->employeeService->updateEmployee($id, $valiData);
            return response()->json(['success' => true, 'message' => 'Update Employee Info Success', 'employee' => $employee], 200);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->employeeService->deleteEmployee($id);
            return response()->json(['success' => true, 'message' => 'Success Delete Employee Info and User Account'], 200);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
