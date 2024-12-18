<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ChefRequest extends FormRequest
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
        $routeIdChef = $this->route('chef');

        return [
            'name' => 'required|string|min:3|unique:chefs,name,' . $routeIdChef . ',uuid',
            'position' => 'required|string|min:3',
            'photo' => $this->method() === 'POST' ? 'required|image|mimetypes:image/jpeg,image/png,image/jpg,image/svg|mimes:jpeg,png,jpg,svg|max:2048' : 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/svg|mimes:jpeg,png,jpg,svg|max:2048',
            'insta_link' => 'nullable',
            'fb_link' => 'nullable',
            'linkedin_link' => 'nullable',
        ];

    }
}
