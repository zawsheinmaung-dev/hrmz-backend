<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaverequestRequest;
use App\Models\LeaveRequest;
use App\Services\LeaverequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaverequestController extends Controller
{
    public function __construct(protected LeaverequestService $leaveRequestService) {}
    public function index()
    {
        $leaveRequest = LeaveRequest::all();
        return response()->json(['success' => true, 'leaveRequest' => $leaveRequest], 200);
    }

    public function store(LeaverequestRequest $request)
    {
        try {
            $valiData = $request->validated();
            $this->leaveRequestService->leaveRequestStore($valiData);
            return response()->json(['success' => true, 'message' => 'LeaveRequest Success'], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        return response()->json(['success' => true, 'leaveRequest' => $leaveRequest], 200);
    }

    public function update($id, LeaverequestRequest $request)
    {
        try {
            $valiData = $request->validated();
            $leaveRequest = $this->leaveRequestService->leaveRequestUpdate($id, $valiData);
            return response()->json(['success' => true, 'message' => 'LeaveRequest Update Success', 'leaveRequest' => $leaveRequest->fresh()], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function leaveRequestApproved($id)
    {
        try {

            $leaveRequest = $this->leaveRequestService->leaveRequestApproved($id);
            return response()->json(['success' => true, 'message' => 'LeaveRequest Approved', 'leaveRequest' => $leaveRequest->fresh()], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function leaveRequestRejected($id)
    {
        try {
            $leaveRequest = $this->leaveRequestService->leaveRequestRejected($id);
            return response()->json(['success' => true, 'message' => 'LeaveRequest Rejected', 'leaveRequest' => $leaveRequest->fresh()], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => true, 'message' => $th->getMessage()], 500);
        }
    }
}
