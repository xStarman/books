<?php

namespace App\Repositories\Subjects;

use App\Models\Assunto;
use App\DTOs\Subjects\SubjectFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\SubjectAlreadyExistsException;

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
            return $query;
        } 
        
        $query->orderBy('CodAs', 'desc');
        return $query;
    }

    public function save(array $data, ?int $id = null): Assunto
    {
        $existingQuery = Assunto::where('Descricao', $data['Descricao']);
        
        if ($id) {
            $existingQuery->where('CodAs', '!=', $id);
        }

        if ($existingQuery->lockForUpdate()->exists()) {
            throw new SubjectAlreadyExistsException();
        }

        if ($id) {
            $assunto = Assunto::findOrFail($id);
            $assunto->update($data);
            return $assunto;
        }

        return Assunto::create($data);
    }
}
