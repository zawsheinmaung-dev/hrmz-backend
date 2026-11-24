<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\LeaveBalanceController;
use App\Http\Controllers\Api\LeaverequestController;
use App\Http\Controllers\Api\LeavetypeController;
use App\Http\Controllers\Api\OvertimeController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\ShiftAssignmentController;
use App\Http\Controllers\api\ShiftController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {});
Route::get('employees/search/{employee}', [EmployeeController::class,'searhEmployee'])->name('employees.search');
Route::get('/departments/search/{department}',[DepartmentController::class,'search'])->name('departments.search');
Route::patch('/departments/{department}/remove-from-branch',[DepartmentController::class,'removeBranch'])->name('departments.remove');
Route::get('/branches/search/{branch}',[BranchController::class,'search'])->name('branches.search');

Route::apiResource('users', UserController::class);
Route::apiResource('branches', BranchController::class);
Route::apiResource('departments', DepartmentController::class);
Route::apiResource('positions', PositionController::class);
Route::apiResource('shifts', ShiftController::class);
Route::apiResource('employees', EmployeeController::class);
Route::apiResource('shiftassignment', ShiftAssignmentController::class);
Route::apiResource('holidays', HolidayController::class);

Route::get('/attendances', [AttendanceController::class, 'index']);
Route::get('/attendance/{attendance}', [AttendanceController::class,'show']);
Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);

Route::patch('/overtimes/{overtime}/approved', [OvertimeController::class, 'approved'])->name('overtimes.approved');
Route::patch('/overtimes/{overtime}/rejected', [OvertimeController::class, 'rejected'])->name('overtimes.rejected');
Route::apiResource('overtimes', OvertimeController::class);
Route::apiResource('leavetypes', LeavetypeController::class);
Route::apiResource('leavebalances', LeaveBalanceController::class);

Route::patch('/leaverequests/{leaverequest}/approved', [LeaverequestController::class, 'leaveRequestApproved']);
Route::patch('/leaverequests/{leaverequest}/rejected', [LeaverequestController::class, 'leaveRequestRejected']);
Route::apiResource('leaverequests', LeaverequestController::class);
