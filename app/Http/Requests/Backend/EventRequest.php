<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        $routeIdEvent = $this->route('event');

        return [
            'name' => 'required|string|min:3|unique:events,name,' . $routeIdEvent . ',uuid',
            'category_id' => 'required|exists:categories,id',
            'image' => $this->method() === 'POST' ? 'required|image|mimetypes:image/jpeg,image/png,image/jpg,image/svg|mimes:jpeg,png,jpg,svg|max:2048' : 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/svg|mimes:jpeg,png,jpg,svg|max:2048',
        ];

    }
}
