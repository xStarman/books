<?php

namespace App\Repositories;

use App\Models\LivroAudit;
use App\DTOs\AuditReportFilterDTO;
use Illuminate\Database\Eloquent\Builder;

class AuditReportRepository
{
    public function getQuery(AuditReportFilterDTO $filters): Builder
    {
        $query = LivroAudit::query()->orderBy('data_alteracao', 'desc');

        if ($filters->titulo) {
            $query->where('Titulo', 'ilike', '%' . $filters->titulo . '%');
        }

        if ($filters->acao && $filters->acao !== 'Todos') {
            $query->where('acao', $filters->acao);
        }

        if ($filters->dataInicial) {
            $query->where('data_alteracao', '>=', $filters->dataInicial . ' 00:00:00');
        }

        if ($filters->dataFinal) {
            $query->where('data_alteracao', '<=', $filters->dataFinal . ' 23:59:59');
        }

        return $query;
    }
}
