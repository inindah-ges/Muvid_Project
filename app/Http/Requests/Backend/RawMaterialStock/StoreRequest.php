<?php

namespace App\Http\Requests\Backend\RawMaterialStock;

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
            'quantity' => 'required|numeric|gt:0',
            'type' => 'required|in:in,out',
            'notes' => 'nullable|string|max:255',
        ];
    }
}
