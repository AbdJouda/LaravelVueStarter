<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user()->getKey())],
            'phone' => ['nullable', Rule::unique('users')->ignore($this->user()->getKey())],
            'profile_photo' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ];
    }
}
