<?php

namespace App\Repositories\Authors;

use App\Models\Autor;
use App\DTOs\Authors\AuthorFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Database\Eloquent\Builder;

class AuthorRepository
{
    /**
     * @param AuthorFiltersDTO $filters
     * @param OrderDTO[] $orders
     */
    public function getFilteredQuery(AuthorFiltersDTO $filters, array $orders = []): Builder
    {
        $query = Autor::query();

        $query->when($filters->Nome, fn($q, $nome) => $q->where('Nome', 'ilike', "%{$nome}%"));

        if (!empty($orders)) {
            foreach ($orders as $order) {
                $query->orderBy($order->field, $order->direction);
            }
        } else {
            $query->orderBy('CodAu', 'desc');
        }

        return $query;
    }
}
