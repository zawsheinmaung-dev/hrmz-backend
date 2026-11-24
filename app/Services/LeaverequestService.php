<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Exception;
use App\Enums\LeaverequestStatus;
use Illuminate\Support\Facades\DB;

class LeaverequestService
{
    public function leaveRequestStore($data)
    {
        DB::transaction(function () use ($data) {
            $start_date = Carbon::parse($data['start_date']);
            $end_date = Carbon::parse($data['end_date']);
            $totalDays = $start_date->diffInDays($end_date) + 1;

            $this->leaveBalanceCheck($data['employee_id'], $data['leave_type_id'], $totalDays);
            $this->leaveOverlapCheck($data['employee_id'], $start_date, $end_date);
            LeaveRequest::create([
                'employee_id' => $data['employee_id'],
                'leave_type_id' => $data['leave_type_id'],
                'start_date' => $start_date->toDateString(),
                'end_date' => $end_date->toDateString(),
                'total_days' => $totalDays,
                'reason' => $data['reason'],

            ]);
        });
    }

    public function leaveRequestUpdate($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $leaveRequest = LeaveRequest::findOrFail($id);
            // dd($leaveRequest);
            $start_date = Carbon::parse($data['start_date']);
            $end_date = Carbon::parse($data['end_date']);
            $totalDays = $start_date->diffInDays($end_date) + 1;
            $this->leaveBalanceCheck($data['employee_id'], $data['leave_type_id'], $totalDays, $id);
            $this->leaveOverlapCheck($data['employee_id'], $start_date, $end_date);
            $leaveRequest->update([
                'start_date' => $start_date->toDateString(),
                'end_date' => $end_date->toDateString(),
                'total_days' => $totalDays,
                'reason' => $data['reason'] ?? "",
            ]);
            return $leaveRequest;
        });
    }

    public function leaveRequestApproved($id)
    {
        return DB::transaction(function () use ($id) {
            $leaveRequest = LeaveRequest::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($leaveRequest->status !== LeaverequestStatus::PENDING->value) {
                throw new Exception('Only Pending Leave Can Approve');
            }
            $leaveBalance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)->where('leave_type_id', $leaveRequest->leave_type_id)->lockForUpdate()->first();

            if ($leaveBalance->remaining_days < $leaveRequest->total_days) {
                throw new Exception('Leave Balance not Enought');
            }
            $leaveBalance->decrement('remaining_days', $leaveRequest->total_days);
            $leaveBalance->increment('used_days', $leaveRequest->total_days);
            $leaveRequest->update([
                'status' => LeaverequestStatus::APPROVED
            ]);
            return $leaveRequest;
        });
    }

    public function leaveRequestRejected($id)
    {
        $leaveRequest =  LeaveRequest::findOrFail($id);
        if ($leaveRequest->status !== LeaverequestStatus::PENDING->value) {
            throw new Exception('Only Pending Leave Can Reject');
        }
        $leaveRequest->update([
            'status' => LeaverequestStatus::REJECTED
        ]);
        return $leaveRequest;
    }

    protected function leaveBalanceCheck($employee_id, $leave_type_id, $totalDays, $updateId = null)
    {
        $leaveBalance = LeaveBalance::where('employee_id', $employee_id)->where('leave_type_id', $leave_type_id)->lockForUpdate()->first();

        $pending = LeaveRequest::where('employee_id', $employee_id)->where('leave_type_id', $leave_type_id)->where('status', LeaverequestStatus::PENDING);
        if ($updateId !== null) {
            $pending->where('id', '!=', $updateId);
        }
        $pendingDays = $pending->first();
        $remainDays = $leaveBalance->remaining_days - $pendingDays?->total_days;
        if (!$leaveBalance) {
            throw new Exception('LeaveType not found');
        }

        if ($totalDays > $leaveBalance->remaining_days) {
            throw new Exception('LeaveBalance not Enought');
        }

        if ($totalDays > $remainDays) {
            throw new Exception('LeaveBalance not Enought');
        }
    }


    protected function leaveOverlapCheck($employee_id, $startDate, $endDate)
    {
        $leaveRequest = LeaveRequest::where('employee_id', $employee_id)->where('status', LeaverequestStatus::class)->whereDate('start_date', '<=', $endDate)->whereDate('end_date', '>=', $startDate)->exists();

        if ($leaveRequest) {
            throw new Exception('Leaverequest Overlap');
        }
    }
}
