<?php
namespace App\Services;

use App\Helpers\CodeGenerate;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class BranchService
{
    public function searchBranch($name)
    {
        $branchs =Branch::where('name','LIKE',"%{$name}%")->orWhere('code','LIKE',"%{$name}%")->limit(5)->get();
        return $branchs;
    }

    public function branchStore($data)
    {
        return DB::transaction(function () use ($data){
            $branch= Branch::create([
                'name'=>$data['name'],
                'address'=>$data['address'],
                'phone'=>$data['phone'],
                'manager_id'=>$data['manager_id'] ?? null
            ]);
            $branch->code =CodeGenerate::generate('BR-',$branch->id,4);
            $branch->save();
            return $branch;
        });
    }

}