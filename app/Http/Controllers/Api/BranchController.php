<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use App\Services\BranchService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    public function __construct(protected BranchService $branchService) {}
    public function search($name)
    {
        try {
            $branches =$this->branchService->searchBranch($name);
            return response()->json(['success'=>true,'branches'=>$branches],200);
        } catch (\Exception $th) {
            return response()->json(['success'=>false,'message'=>$th->getMessage()],500);
        }
    }

    public function index()
    {
        $branches = Branch::with('branchManager')->withCount('employees','departments')->get();
        return response()->json(['success'=>true,'branches'=>$branches]);
    }

    public function store(BranchRequest $request)
    {
        try {
            $this->branchService->branchStore($request->validated());
            return response()->json(['success' => 'Branches Create Success'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $branch = Branch::with('branchManager','departments')->withCount('employees','departments')->findOrFail($id);
        return response()->json(['branche'=>$branch], 200);
    }


    public function update($id, BranchRequest $request)
    {
        Log::alert('branch update data',$request->validated());
        $branch = Branch::findOrFail($id);
        $branch->update($request->validated());
        return response()->json(['success' => 'Branch Update Success', 'branch' => $branch->fresh()], 200);
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return response()->json(['success' => 'Branch Delete Success'], 200);
    }
}
