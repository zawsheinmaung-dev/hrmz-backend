<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftAssignmentRequest;
use App\Models\ShiftAssignment;
use App\Services\ShiftAssignmentService;

class ShiftAssignmentController extends Controller
{
    public function __construct(protected ShiftAssignmentService $shiftAssignmentService) {}
    public function index()
    {
        $shiftAsign = ShiftAssignment::all();
        return response()->json(['success' => true, 'shiftAssignments' => $shiftAsign], 200);
    }

    public function store(ShiftAssignmentRequest $request)
    {
        try {
            $valiData = $request->validated();
            $this->shiftAssignmentService->shiftAssignStore($valiData);
            return response()->json(['success' => true, 'message' => 'Shift Assign Success'], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $shiftAsignment = ShiftAssignment::findOrFail($id);
        return response()->json(['success' => true, 'shiftAssignment' => $shiftAsignment], 200);
    }


    public function destroy($id)
    {
        $shiftAsignment = ShiftAssignment::findOrFail($id);
        $shiftAsignment->delete();
        return response()->json(['success' => true, 'message' => 'Success remove on current shift'], 200);
    }
}
