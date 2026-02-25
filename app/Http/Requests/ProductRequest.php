<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Anyone may create/update a product in this app; authorization is handled elsewhere
     * if needed.
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // on update we can make fields sometimes
            $rules['name'] = ['sometimes', 'string', 'max:255'];
            $rules['price'] = ['sometimes', 'numeric', 'min:0'];
        }

        return $rules;
    }
}
