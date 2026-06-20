<?php

namespace App\Services;

use App\Models\Assunto;

class GetSubjectListService
{
    public function execute(array $filters = [], array $order = [], int $pageSize = 25)
    {
        $query = Assunto::query();

        $query->when(isset($filters['Descricao']), fn($q) => $q->where('Descricao', 'like', "%{$filters['Descricao']}%"));

        if (!empty($order)) {
            foreach ($order as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        } else {
            $query->orderBy('Descricao', 'asc');
        }

        return $query->paginate($pageSize);
    }
}
