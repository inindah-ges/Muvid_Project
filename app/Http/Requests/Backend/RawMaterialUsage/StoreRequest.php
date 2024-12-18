<?php

namespace App\Http\Requests\Backend\RawMaterialUsage;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'quantity_used' => 'required|numeric|gt:0',
            'date' => 'required|date|before_or_equal:today',
        ];
    }
}
