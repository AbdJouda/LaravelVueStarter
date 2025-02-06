<?php

namespace App\Http\Requests\V1;

use App\Enums\TodoPriorities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TodoRequest extends FormRequest
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
        return [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['boolean'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', Rule::in(TodoPriorities::getAllValues())],

        ];
    }
}
