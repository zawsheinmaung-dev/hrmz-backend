<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_code',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'national_id',
        'phone',
        'personal_email',
        'address',
        'branch_id',
        'department_id',
        'position_id',
        'manager_id',
        'salary_structure_id',
        'join_date',
        'resign_date',
        'employment_type',
        'employment_status',
        'profile_photo'        
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function shiftAssignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }
    
    public function manager()
    {
        return $this->belongsTo(Employee::class,'manager_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
}
