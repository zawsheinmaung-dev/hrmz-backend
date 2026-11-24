<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Overtime extends Model
{
    /** @use HasFactory<\Database\Factories\OvertimeFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'employee_id',
        'attendance_id',
        'start_time',
        'end_time',
        'reason',
        'status',
        'approved_by',
        'date',
        'total_minutes'
    ];
}
