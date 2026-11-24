<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes,HasFactory;
    
    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'manager_id'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function branchManager()
    {
        return $this->belongsTo(Employee::class,'manager_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
