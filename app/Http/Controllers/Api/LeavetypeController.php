<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeavetypeController extends Controller
{
    public function index()
    {
        $leave = LeaveType::all();
        return response()->json(['success' => true, '$leaveType' => $leave], 200);
    }

    public function store(Request $request)
    {
        $valiData = $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'unique:leave_types,name'],
                'days_allowed_per_year' => ['required', 'integer', 'min:0'],
                'is_paid' => ['required', 'boolean']
            ]
        );
        LeaveType::create($valiData);
        return response()->json(['success' => true, 'message' => 'Leave Type Create Success'], 201);
    }

    public function show($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        return response()->json(['success' => true, 'leaveType' => $leaveType], 200);
    }

    public function update($id, Request $request)
    {
        $leaveType = LeaveType::findOrFail($id);
        $valiData = $request->validate(
            [
                'name' => ['required', 'string', 'max:255', "unique:leave_types,name,{$id}"],
                'days_allowed_per_year' => ['required', 'integer', 'min:0'],
                'is_paid' => ['required', 'boolean']
            ]
        );
        $leaveType->update($valiData);
        return response()->json(['success' => true, 'message' => 'LeaveType Update Succes', 'leaveType' => $leaveType->fresh()], 200);
    }

    public function destroy($id) 
    {
        $leaveType = LeaveType::findOrFail($id);
        $leaveType->delete();
        return response()->json(['success'=>true,'message'=>'LeaveType Deleting Success'],200);
    }
}
