<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class RawMaterialRequest extends FormRequest
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
        $routeIdRawMaterial = $this->route('raw_material');

        return [
            'name' => 'required|min:3|unique:raw_materials,name,' . $routeIdRawMaterial . ',uuid',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|numeric',
            'unit' => 'required|string|in:pcs,gram,kg,liter,ml',
        ];
    }
}
