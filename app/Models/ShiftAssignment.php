<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftAssignment extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftAssignmentFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'effective_date'
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
