<?php

namespace App\Http\Requests\Backend\Forecasting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActualRequest extends FormRequest
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
            'forecast_id' => 'required|exists:forecasting_results,id',
            'actual_usage' => 'required|numeric|min:0',
        ];
    }
}
