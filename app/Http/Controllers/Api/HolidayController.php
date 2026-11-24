<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::all();
        return response()->json(['success' => true, 'holidays' => $holidays]);
    }

    public function store(Request $request)
    {
        $valiData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'holiday_date' => ['required', 'after_or_equal:today'],
            'is_recurring' => ['sometimes', 'boolean'],
            'description' => ['nullable', 'string']
        ]);
        Holiday::create($valiData);
        return response()->json(['success' => true, 'message' => 'Holiday add scueess'], 200);
    }

    public function show($id)
    {
        $holiday = Holiday::findOrFail($id);
        return response()->json(['success' => true, 'holiday' => $holiday], 200);
    }

    public function update($id, Request $request)
    {
        $valiData = $request->validate([
            'name' => ['required', 'string', 'max:255',],
            'holiday_date' => ['required', 'after_or_equal:today'],
            'is_recurring' => ['sometimes', 'boolean'],
            'description' => ['sometimes', 'string']
        ]);
        $holiday = Holiday::findOrFail($id);
        $holiday->update($valiData);
        return response()->json(['success' => true, 'holiday' => $holiday->fresh()], 200);
    }

    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();
        return response()->json(['success' => true, 'message' => 'Holiday Delete Success'], 200);
    }
}
