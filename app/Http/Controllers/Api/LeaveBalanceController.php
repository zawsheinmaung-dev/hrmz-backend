<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeavebalanceRequest;
use App\Models\LeaveBalance;
use App\Services\LeavebalanceService;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function __construct(protected LeavebalanceService $leaveBalancsService) {}

    public function index()
    {
        $leaveBalance = LeaveBalance::all();
        return response()->json(['success' => true, 'leaveBalance' => $leaveBalance], 200);
    }

    public function store(LeavebalanceRequest $request)
    {
        try {
            $valiDate = $request->validated();
            $this->leaveBalancsService->leaveBalanceStore($valiDate);
            return response()->json(['success' => true, 'message' => 'Leave Balance Create Success'], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $leaveBalance = LeaveBalance::findOrFail($id);
        return response()->json(['success' => true, 'leaveBalance' => $leaveBalance], 200);
    }

    public function update($id, LeavebalanceRequest $request)
    {
        try {
            $valiDate = $request->validated();
            $leaveBalance = $this->leaveBalancsService->leaveBalanceUpdate($id, $valiDate);
            return response()->json(['success' => true, 'message' => 'Leave Balance Update Success', 'leaveBalance' => $leaveBalance], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->leaveBalancsService->leaveBalanceDelete($id);
            return response()->json(['success' => true, 'message' => 'Leave Balance Deleting Success'], 200);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
