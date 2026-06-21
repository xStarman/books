<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Titulo' => ['nullable', 'string'],
            'Editora' => ['nullable', 'string'],
            'Edicao' => ['nullable', 'string'],
            'AnoPublicacao' => ['nullable', 'string'],
            'Preco' => ['nullable', 'string'],
            'autores' => ['nullable', 'array'],
            'autores.*' => ['integer'],
            'assuntos' => ['nullable', 'array'],
            'assuntos.*' => ['integer'],
        ];
    }
}
