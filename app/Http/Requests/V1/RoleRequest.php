<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $role = $this->route('role');

        $tableNames = config('permission.table_names');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique($tableNames['roles'])->ignore($role?->getKey())],
            'roles' => ['array'],
            'rules.*' => ['nullable', 'string', 'uuid'],
            'permissions' => ['array'],
            'permissions.*' => ['nullable', 'string', 'uuid'],
            'apply_to_users' => ['sometimes', 'boolean'],

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => Str::slug($this->name, '_'),
        ]);
    }



}
