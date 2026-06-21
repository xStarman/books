<?php

namespace App\Http\Requests\Authors;

use Illuminate\Foundation\Http\FormRequest;

class ListAuthorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'page_size' => ['nullable', 'integer', 'min:1', 'max:100'],
            'filters' => ['nullable', 'array'],
            'filters.Nome' => ['nullable', 'string'],
            'order' => ['nullable', 'array'],
        ];
    }
}
