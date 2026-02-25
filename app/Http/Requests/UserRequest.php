<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * The email rule should ignore the current user when updating.
     */
    public function rules(): array
    {
        // route-model binding will provide a User instance when updating/reading
        $userId = $this->route('user')?->id;

        $passwordRules = $this->isMethod('post')
            ? ['required', 'string', 'min:8']
            : ['sometimes', 'nullable', 'string', 'min:8'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => array_filter([
                $this->isMethod('post') ? 'required' : 'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ]),
            'password' => $passwordRules,
        ];
    }
}
