<?php

namespace App\Http\Requests\Books;

use App\Models\Autor;
use App\Models\Assunto;
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
            'autores.*' => [
                function ($attribute, $value, $fail) {
                    if (is_numeric($value)) {
                        if (!Autor::where('CodAu', $value)->exists()) {
                            $fail('O autor selecionado é inválido.');
                        }
                        return;
                    } 
                    
                    if (is_string($value) && str_starts_with($value, 'novo:')) {
                        return;
                    } 
                    
                    $fail('Formato de autor inválido.');
                }
            ],
            'assuntos' => ['required', 'array', 'min:1'],
            'assuntos.*' => [
                function ($attribute, $value, $fail) {
                    if (is_numeric($value)) {
                        if (!Assunto::where('CodAs', $value)->exists()) {
                            $fail('A categoria selecionada é inválida.');
                        }
                        return;
                    } 
                    
                    if (is_string($value) && str_starts_with($value, 'novo:')) {
                        return;
                    } 
                    
                    $fail('Formato de categoria inválido.');
                }
            ],
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
