<?php

namespace App\Repositories;

use App\Models\Assunto;
use App\DTOs\SubjectFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Database\Eloquent\Builder;

class SubjectRepository
{
    /**
     * @param SubjectFiltersDTO $filters
     * @param OrderDTO[] $orders
     */
    public function getFilteredQuery(SubjectFiltersDTO $filters, array $orders = []): Builder
    {
        $query = Assunto::query();

        $query->when($filters->Descricao, fn($q, $descricao) => $q->where('Descricao', 'ilike', "%{$descricao}%"));

        if (!empty($orders)) {
            foreach ($orders as $order) {
                $query->orderBy($order->field, $order->direction);
            }
        } else {
            $query->orderBy('Descricao', 'asc');
        }

        return $query;
    }
}
