<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'branch_id',
        'name',
        'code',
        'head_department_id',
        'head_emp_id',
        'description'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function headDepartment()
    {
        return $this->belongsTo(Department::class,'head_department_id');
    }

    public function departmentManager()
    {
        return $this->belongsTo(Employee::class,'head_emp_id');
    }
}
