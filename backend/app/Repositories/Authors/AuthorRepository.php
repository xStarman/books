<?php

namespace App\Repositories\Authors;

use App\Models\Autor;
use App\DTOs\Authors\AuthorFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\AuthorAlreadyExistsException;

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
            return $query;
        } 
        
        $query->orderBy('CodAu', 'desc');
        return $query;
    }

    public function save(array $data, ?int $id = null): Autor
    {
        $existingQuery = Autor::where('Nome', $data['Nome']);
        
        if ($id) {
            $existingQuery->where('CodAu', '!=', $id);
        }

        if ($existingQuery->lockForUpdate()->exists()) {
            throw new AuthorAlreadyExistsException();
        }

        if ($id) {
            $autor = Autor::findOrFail($id);
            $autor->update($data);
            return $autor;
        }

        return Autor::create($data);
    }
}
