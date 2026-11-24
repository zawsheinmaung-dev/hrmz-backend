<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\ShiftAssignment;
use Carbon\Carbon;
use Exception;

class AttendanceService
{
    public function storeCheckIn($data)
    {

        $shiftAssign = ShiftAssignment::with('shift')->where('employee_id', $data['employee_id'])->first();
        if (!$shiftAssign) {
            throw new Exception('You are not having any assign!. Please Connect to HR or Admin');
        }

        $attendance = Attendance::where('employee_id', $data['employee_id'])->where('attendance_date', $data['attendance_date'])->whereNotNull('check_in')->first();

        if ($attendance) {
            throw new Exception('You are already check in');
        }

        $shiftStart = now()->copy()->setTimeFromTimeString($shiftAssign->shift->start_time);
        $limitLate = $shiftStart->copy()->addMinutes($shiftAssign->shift->grace_minutes);
        $lateMinutes = now()->gt($limitLate) ? (int) $limitLate->diffInMinutes(now()) : 0;
        $status = $lateMinutes > 0 ? AttendanceStatus::LATE : AttendanceStatus::PRESENT;

        Attendance::create([
            'employee_id' => $data['employee_id'],
            'attendance_date' => $data['attendance_date'],
            'check_in' => today()->now()->format('H:i:s'),
            'status' => $status,
            'late_minutes' => $lateMinutes
        ]);
    }

    public function storeCheckOut($data)
    {
        $shiftAssign = ShiftAssignment::with('shift')->where('employee_id', $data['employee_id'])->first();
        if (!$shiftAssign) {
            throw new Exception('You are not having any assign!. Please Connect to HR or Admin');
        }

        $attendance = Attendance::where('employee_id', $data['employee_id'])->where('attendance_date', $data['attendance_date'])->whereNotNull('check_in')->first();
        if (!$attendance) {
            throw new Exception('You have not check in record');
        }

        if ($attendance->check_out !== null) {
            throw new Exception('You Are Already Chcke Out');
        }

        $shiftEnd = now()->copy()->setTimeFromTimeString($shiftAssign->shift->end_time);
        $workingMinutes = max(0, Carbon::parse($attendance->check_in)->diffInMinutes(now()));
        $earlyOutMinutes = max(0, now()->lt($shiftEnd) ? (int) now()->diffInMinutes($shiftEnd) : 0);
        $attendance->update([
            'check_out' => today()->now()->format('H:i:s'),
            'working_minutes' => $workingMinutes,
            'early_leave_minutes' => $earlyOutMinutes
        ]);
        return $attendance;
    }
}
