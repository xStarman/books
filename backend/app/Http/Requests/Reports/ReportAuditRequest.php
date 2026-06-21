<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class ReportAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Titulo' => ['nullable', 'string'],
            'acao' => ['nullable', 'string', 'in:Todos,UPDATE,DELETE'],
            'dataInicial' => ['nullable', 'date'],
            'dataFinal' => ['nullable', 'date'],
        ];
    }
}
