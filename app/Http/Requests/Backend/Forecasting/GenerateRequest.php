<?php

namespace App\Http\Requests\Backend\Forecasting;

use Illuminate\Foundation\Http\FormRequest;

class GenerateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'raw_material_id' => 'required|exists:raw_materials,id',
            'period' => 'required|integer|min:3|max:12',
        ];
    }
}
