<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class SaveBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Titulo' => ['required', 'string', 'max:40'],
            'Editora' => ['required', 'string', 'max:40'],
            'Edicao' => ['required', 'integer', 'min:1'],
            'AnoPublicacao' => ['required', 'integer', 'min:1000', 'max:9999'],
            'Preco' => ['required', 'numeric', 'min:0'],
            'autores' => ['required', 'array', 'min:1'],
            'autores.*' => ['integer', 'exists:autores,CodAu'],
            'assuntos' => ['required', 'array', 'min:1'],
            'assuntos.*' => ['integer', 'exists:assuntos,CodAs'],
        ];
    }



    public function attributes(): array
    {
        return [
            'autores.*' => 'autor',
            'assuntos.*' => 'assunto',
        ];
    }
}
