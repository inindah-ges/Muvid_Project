<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'payment_method' => 'required|in:cash,qris,gopay,shopeepay,bca_va,bni_va,bri_va,mandiri_va,permata_va,cimb_va,bsi_va,danamon_va,credit_card',
            'payment_amount' => 'required_if:payment_method,cash|numeric|min:0',
        ];
    }
}
