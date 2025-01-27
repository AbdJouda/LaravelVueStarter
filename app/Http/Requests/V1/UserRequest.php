<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {

        $user = $this->route('user');

        return  [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user?->getKey())],
            'phone' => ['nullable', Rule::unique('users')->ignore($user?->getKey())],
            'is_active' => ['required', 'boolean'],
            'roles' => ['array'],
            'rules.*' => ['nullable', 'string', 'uuid'],
            'permissions' => ['array'],
            'permissions.*' => ['nullable', 'string', 'uuid'],
        ];

    }
}
