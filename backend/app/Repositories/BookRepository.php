<?php

namespace App\Repositories;

use App\Models\Livro;
use App\DTOs\BookFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Database\Eloquent\Builder;

class BookRepository
{
    /**
     * @param BookFiltersDTO $filters
     * @param OrderDTO[] $orders
     */
    public function getFilteredQuery(BookFiltersDTO $filters, array $orders = []): Builder
    {
        $query = Livro::with(['autores', 'assuntos']);

        $query->when($filters->Titulo, fn($q, $titulo) => $q->where('Titulo', 'like', "%{$titulo}%"));
        $query->when($filters->Editora, fn($q, $editora) => $q->where('Editora', 'like', "%{$editora}%"));
        $query->when($filters->Edicao !== null, fn($q) => $q->where('Edicao', $filters->Edicao));
        $query->when($filters->AnoPublicacao !== null, fn($q) => $q->where('AnoPublicacao', $filters->AnoPublicacao));
        
        $query->when($filters->Autor !== null, function($q) use ($filters) {
            $q->whereHas('autores', fn($q2) => $q2->where('CodAu', $filters->Autor));
        });

        $query->when($filters->Assunto !== null, function($q) use ($filters) {
            $q->whereHas('assuntos', fn($q2) => $q2->where('CodAs', $filters->Assunto));
        });

        if (!empty($orders)) {
            foreach ($orders as $order) {
                $query->orderBy($order->field, $order->direction);
            }
        } else {
            $query->orderBy('Titulo', 'asc');
        }

        return $query;
    }
}
