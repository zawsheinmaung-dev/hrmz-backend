<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class Shift extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_minutes',
        'break_minutes',
        'working_days'
    ];

    protected function casts()
    {
        return[
            'working_days'=>'array'
        ];
    }

    public function shiftAssignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }


}
