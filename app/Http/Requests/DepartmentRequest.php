<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
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
            'branch_id' => ['required', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->where('branch_id', $this->branch_id)],
            'head_department_id' => ['required', 'exists:departments,id'],
            'head_emp_id' => ['required', 'exists:employees,id'],
            'description' => ['nullable', 'string', 'max:255']
        ];
    }

    public function update()
    {
        $depId = $this->route('department');
        return [
            'branch_id' => ['nullable', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->where('branch_id', $this->branch_id)->ignore($depId)],
            'description' => ['nullable', 'string', 'max:255'],
            'head_department_id' => ['nullable', 'exists:departments,id'],
            'head_emp_id' => ['nullable', 'exists:employees,id'],
        ];
    }
}
