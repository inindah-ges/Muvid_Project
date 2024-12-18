<?php

namespace App\Http\Requests\Backend\RawMaterialUsage;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'raw_material_id' => 'nullable|exists:raw_materials,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from|before_or_equal:today',
        ];
    }

    public function messages()
    {
        return [
            'raw_material_id.exists' => 'Selected material is invalid',
            'date_from.required' => 'Start date is required',
            'date_from.date' => 'Invalid start date format',
            'date_to.required' => 'End date is required',
            'date_to.date' => 'Invalid end date format',
            'date_to.after_or_equal' => 'End date must be after or equal to start date',
            'date_to.before_or_equal' => 'End date cannot be in the future',
        ];
    }
}
