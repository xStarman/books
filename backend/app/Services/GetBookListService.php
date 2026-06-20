<?php

namespace App\Services;

use App\Models\Livro;

class GetBookListService
{
    public function execute(array $filters = [], array $order = [], int $pageSize = 25)
    {
        $query = Livro::query();

        $query->when(isset($filters['Titulo']), fn($q) => $q->where('Titulo', 'like', "%{$filters['Titulo']}%"));
        $query->when(isset($filters['Editora']), fn($q) => $q->where('Editora', 'like', "%{$filters['Editora']}%"));
        $query->when(isset($filters['Edicao']), fn($q) => $q->where('Edicao', $filters['Edicao']));
        $query->when(isset($filters['AnoPublicacao']), fn($q) => $q->where('AnoPublicacao', $filters['AnoPublicacao']));

        if (!empty($order)) {
            foreach ($order as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        } else {
            $query->orderBy('Titulo', 'asc');
        }

        return $query->paginate($pageSize);
    }
}
