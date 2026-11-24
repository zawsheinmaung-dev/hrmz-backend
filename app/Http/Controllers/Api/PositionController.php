<?php

namespace App\Http\Controllers\Api;

use App\Enums\PositionLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::withCount('employees')->get();
        return response()->json(['success' => true, 'positions' => $positions,'levels'=>PositionLevel::cases()], 200);
    }

    public function show($id)
    {
        $pos = Position::with('employees.branch','employees.department')->withCount('employees')->findOrFail($id);
        return response()->json(['success' => true, 'position' => $pos,'levles'=>PositionLevel::cases()], 200);
    }

    public function store(PositionRequest $request)
    {
        $pos = $request->validated();
        Log::alert('position',$pos);
        Position::create($pos);
        return response()->json(['success' => true, 'message' => 'Position Create Success'], 201);
    }

    public function update($id, PositionRequest $request)
    {
        $valiData = $request->validated();
        $pos = Position::findOrFail($id);
        $pos->fresh();
        return response()->json(['success' => true, 'message' => 'Position Update Success', 'position' => $pos,'levles'=>PositionLevel::cases()], 200);
    }

    public function destroy($id)
    {
        $pos = Position::findOrFail($id);
        $pos->delete();
        return response()->json(['success' => true, 'message' => 'Position Delete Success'], 200);
    }
}
