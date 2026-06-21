<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class ListBooksRequest extends FormRequest
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
            'filters.Titulo' => ['nullable', 'string'],
            'filters.Editora' => ['nullable', 'string'],
            'filters.Edicao' => ['nullable', 'integer'],
            'filters.AnoPublicacao' => ['nullable', 'integer'],
            'order' => ['nullable', 'array'],
        ];
    }
}
