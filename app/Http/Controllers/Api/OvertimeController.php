<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OvertimeRequest;
use App\Services\OvertimeService;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function __construct(protected OvertimeService $overtimeService) {}
    public function index() {}

    public function store(OvertimeRequest $request)
    {
        try {
            $valiDate = $request->validated();
            $this->overtimeService->overtimeStore($valiDate);
            return response()->json(['success' => true, 'message' => 'Overtime request success'], 201);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function update($id, OvertimeRequest $request)
    {
        try {
            $valiDate = $request->validated();
            $overtime = $this->overtimeService->overtimeUpdate($id, $valiDate);
            return response()->json(['success' => true, 'message' => 'Overtime request update success', 'overtime' => $overtime->fresh()], 200);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function approved($id)
    {
        try {
            $overtime = $this->overtimeService->overtimeApproved($id);
            return response()->json(['success' => true, 'message' => 'Overtime Approved Success', 'overtime' => $overtime->fresh()], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function rejected($id)
    {
        try {
            $overtime = $this->overtimeService->overtimeRejected($id);
            return response()->json(['success' => true, 'message' => 'Overtime Approved Success', 'overtime' => $overtime->fresh()], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
