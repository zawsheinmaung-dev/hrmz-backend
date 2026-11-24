<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Exception;

class LeavebalanceService
{
    public function leaveBalanceStore($data)
    {
        $leaveType = LeaveType::findOrFail($data['leave_type_id']);

        $leaveBalance = LeaveBalance::where('employee_id', $data['employee_id'])->where('leave_type_id', $data['leave_type_id'])->where('year', $data['year'])->exists();

        if ($leaveBalance) {
            throw new Exception('Already Have this LeaveType');
        }

        LeaveBalance::create([
            'employee_id' => $data['employee_id'],
            'leave_type_id' => $leaveType->id,
            'year' => $data['year'],
            'allocated_days' => $leaveType->days_allowed_per_year,
            'remaining_days' => $leaveType->days_allowed_per_year,
        ]);
    }

    public function leaveBalanceUpdate($id, $data)
    {
        $leaveBalance = LeaveBalance::findOrFail($id);
        $leaveType = LeaveType::findOrFail($data['leave_type_id']);
        $remaining_days = $leaveType->days_allowed_per_year - $leaveBalance->used_days;
        $leaveBalance->update([
            'employee_id' => $data['employee_id'],
            'leave_type_id' => $leaveType->id,
            'year' => $data['year'],
            'allocated_days' => $leaveType->days_allowed_per_year,
            'remaining_days' => $remaining_days,
        ]);
        return $leaveBalance;
    }

    public function leaveBalanceDelete($id)
    {
        $leaveBalance = LeaveBalance::findOrFail($id);
        if ($leaveBalance->used_days > 0) {
            throw new Exception('Cannot Delete this leave balance that has been used');
        }
        $leaveBalance->delete();
    }
}
