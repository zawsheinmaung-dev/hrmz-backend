<?php

namespace App\Services;

use App\Enums\OvertimeStatus;
use App\Models\Overtime;
use Carbon\Carbon;
use Exception;

class OvertimeService
{
    public function overtimeStore($data)
    {
        $startTim = Carbon::parse($data['start_time']);
        $endTim = Carbon::parse($data['end_time']);
        $totalMinutes = $startTim->diffInMinutes($endTim);

        $overtime = Overtime::where('employee_id', $data['employee_id']->where('attendance_id', $data['attendance_id']))->exists();
        if (!$overtime) {
            throw new Exception('You not have attendance for this daya');
        }
        Overtime::create([
            'employee_id' => $data['employee_id'],
            'attendance_id' => $data['attendance_id'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'total_minutes' => $totalMinutes,
            'date' => $data['date'],
            'reason' => $data['reason']
        ]);
    }

    public function overtimeUpdate($id, $data)
    {
        $overtime = Overtime::findOrFail($id);
        if ($overtime->status !== OvertimeStatus::PENDING) {
            throw new Exception('Only Pending Overtime Status Can update');
        }

        $startTim = Carbon::parse($data['start_time']);
        $endTim = Carbon::parse($data['end_time']);
        $totalMinutes = $startTim->diffInMinutes($endTim);

        $overtime->update([
            'employee_id' => $data['employee_id'],
            'attendance_id' => $data['attendance_id'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'total_minutes' => $totalMinutes,
            'date' => $data['date'],
            'reason' => $data['reason']
        ]);
        return $overtime;
    }

    public function overtimeApproved($id)
    {
        $overtime = Overtime::findOrFail($id);
        if ($overtime->status !== OvertimeStatus::PENDING) {
            throw new Exception('Only Pending Overtime can Approved');
        }
        $overtime->update([
            'status' => OvertimeStatus::APPROVED,
            'approved_by' => auth()->id()
        ]);
        return $overtime->fresh();
    }

    public function overtimeRejected($id)
    {
        $overtime = Overtime::findOrFail($id);

        if ($overtime->status !== OvertimeStatus::PENDING) {
            throw new Exception('Only Pending Overtime can Rejected');
        }
        $overtime->update([
            'status' => OvertimeStatus::REJECTED,
            'approved_by' => auth()->id()
        ]);
        return $overtime;
    }
}
