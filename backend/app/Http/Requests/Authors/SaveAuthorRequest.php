<?php

namespace App\Http\Requests\Authors;

use Illuminate\Foundation\Http\FormRequest;

class SaveAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Nome' => ['required', 'string', 'max:40'],
        ];
    }
}
