<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name'=>['required','unique:branches,name'],
            'address'=>['required','string','max:255'],
            'phone'=>['required','string'],
            'code'=>['nullable','unique:branches,code'],
            'manager_id'=>['nullable','exists:employees,id']
        ];
    }

    public function update()
    {
        $branchId=$this->route('branch');
        return [
            'name'=>['required',"unique:branches,name,{$branchId}"],
            'address'=>['required','string','max:255'],
            'phone'=>['required','string'],
            'manager_id'=>['nullable','exists:employees,id']
        ];
    }
}
