<?php
namespace App\Services;

use App\Models\ShiftAssignment;
use Exception;

class ShiftAssignmentService
{
    public function shiftAssignStore($data)
    {
        $shiftAssign =ShiftAssignment::where('employee_id',$data['employee_id'])->first();
        if($shiftAssign)
            {
                throw new Exception($shiftAssign->employee->first_name.' '. $shiftAssign->employee->last_name .' '.'already assign on' .' '. $shiftAssign->shift->name. '.' . 'Remove the current assignment first.');
            }
        ShiftAssignment::create($data);
    }
}