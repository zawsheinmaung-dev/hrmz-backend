<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService) {}
    public function index()
    {
        $attendances = Attendance::all();
        return response()->json(['success' => true, 'attendances' => $attendances], 200);
    }

    public function checkIn(AttendanceRequest $request)
    {
        try {
            $valiData = $request->validated();
            $this->attendanceService->storeCheckIn($valiData);
            return response()->json(['success' => true, 'message' => 'Check in success'], 201);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function checkOut(AttendanceRequest $request)
    {
        try {
            $valiData = $request->validated();
            $attendance = $this->attendanceService->storeCheckOut($valiData);
            return response()->json(['success' => true, 'attendance' => $attendance], 200);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        return response()->json(['success' => true, 'attendance' => $attendance], 200);
    }
}
