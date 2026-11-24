<?php

namespace App\Http\Requests;

use App\Enums\PositionLevel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', Rule::unique('positions', 'title')->where('level',$this->level)],
            'level' => ['required', Rule::enum(PositionLevel::class)],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function update()
    {
        $posId = $this->route('position');
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('positions', 'title')->ignore($posId)],
            'level' => ['required', Rule::enum(PositionLevel::class)],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
