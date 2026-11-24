<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftRequest;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return response()->json(['success'=>true,'shifts'=>$shifts],200);
    }

    public function store(ShiftRequest $request)
    {
        $valiData=$request->validated();
        Shift::create($valiData);
        return response()->json(['success'=>true,'message'=>'Shift Create Success'],201);
    }

    public function show($id)
    {
        $shift = Shift::findOrFail($id);
        return response()->json(['success'=>true,'shift'=>$shift]);
    }

    public function update($id, ShiftRequest $request)
    {
        $valiData =$request->validated();
        $shift =Shift::findOrFail($id);
        $shift->update($valiData);
        return response()->json(['success'=>true,'message'=>'Update Shift Success','shift'=>$shift->fresh()],200);
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return response()->json(['success'=>true,'message'=>'Shift Delete Success'],200);
    }
}
