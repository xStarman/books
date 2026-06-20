<?php

namespace App\Services;

use App\Models\Autor;

class GetAuthorListService
{
    public function execute(array $filters = [], array $order = [], int $pageSize = 25)
    {
        $query = Autor::query();

        $query->when(isset($filters['Nome']), fn($q) => $q->where('Nome', 'like', "%{$filters['Nome']}%"));

        if (!empty($order)) {
            foreach ($order as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        } else {
            $query->orderBy('Nome', 'asc');
        }

        return $query->paginate($pageSize);
    }
}
