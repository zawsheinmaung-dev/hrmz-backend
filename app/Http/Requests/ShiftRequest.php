<?php

namespace App\Http\Requests;

use App\Enums\WorkingDay;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShiftRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:shifts,name'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => [
                'required',
                'date_format:H:i:s',
                'after:start_time',

            ],
            'working_days' => ['sometimes', 'required', 'array', 'min:1'],
            'working_days.*' => [
                'required',
                'string',
                'distinct',
                Rule::enum(WorkingDay::class),
            ],
        ];
    }

    public function update()
    {
        $shiftId = $this->route('shift');
        return [
            'name' => ['sometimes','required','string','max:255',"unique:shifts,name,{$shiftId}"],
            'start_time' => ['sometimes','required','date_format:H:i:s'],
            'end_time' => [
                'sometimes','required',
                'date_format:H:i:s',
                'after:start_time',
            ],
            'working_days' => ['sometimes','required','array','min:1'],
            'working_days.*' => [
                'required',
                'string',
                'distinct',
                Rule::enum(WorkingDay::class),
            ],
        ];
    }
}
