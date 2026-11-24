<?php

namespace App\Http\Requests;

use App\Enums\EmployeeStatus;
use App\Enums\EmploymentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return $this->isMethod('POST') ? $this->store() : $this->update();
    }

    public function store()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => ['nullable', 'date'],
            'national_id' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'personal_email' => ['required', 'email', 'max:255', 'unique:employees,personal_email'],
            'address' => ['nullable', 'string'],
            'branch_id' => ['required', 'exists:branches,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'manager_id' => ['nullable', 'exists:employees,id'],
            'salary_structure_id' => ['nullable'],
            'join_date' => ['required', 'date'],
            'resign_date' => ['nullable', 'date', 'after_or_equal:join_date'],
            'employment_type' => ['required', Rule::enum(EmploymentType::class)],
            'profile_photo' => ['nullable', 'image']
        ];
    }

    public function update()
    {
        $empId = $this->route('employee');
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => ['nullable', 'date'],
            'national_id' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'personal_email' => ['required', 'email', 'max:255', Rule::unique('employees','personal_email')->ignore($empId)],
            'address' => ['nullable', 'string'],
            'branch_id' => ['required',Rule::exists('branches','id')->whereNull('deleted_at')],
            'department_id' => ['required', "exists:departments,id"],
            'position_id' => ['required', "exists:positions,id"],
            'manager_id' => ['nullable', "exists:employees,id"],
            'salary_structure_id' => ['nullable'],
            'join_date' => ['required', 'date'],
            'resign_date' => ['nullable', 'date', 'after_or_equal:join_date'],
            'employment_type' => ['required', Rule::enum(EmploymentType::class)],
            'employment_status' => ['required', Rule::enum(EmployeeStatus::class)],
            'profile_photo' => ['nullable', 'image']
        ];
    }
}
