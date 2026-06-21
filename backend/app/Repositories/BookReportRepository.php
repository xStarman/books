<?php

namespace App\Repositories;

use App\Models\VwLivrosRelatorio;
use App\DTOs\BookReportFilterDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BookReportRepository
{
    public function getQuery(BookReportFilterDTO $filters): Builder
    {
        $query = VwLivrosRelatorio::query();

        if ($filters->titulo) {
            $query->where('Titulo', 'ilike', '%' . $filters->titulo . '%');
        }

        if ($filters->editora) {
            $query->where('Editora', 'ilike', '%' . $filters->editora . '%');
        }

        $this->applyRangeFilter($query, 'Edicao', $filters->edicao);
        $this->applyRangeFilter($query, 'AnoPublicacao', $filters->anoPublicacao);
        $this->applyRangeFilter($query, 'Preco', $filters->preco, true);

        if (!empty($filters->autores)) {
            $query->whereExists(function ($q) use ($filters) {
                $q->select(DB::raw(1))
                  ->from('livro_autor')
                  ->whereColumn('livro_autor.Livro_CodL', 'vw_livros_relatorio.CodL')
                  ->whereIn('livro_autor.Autor_CodAu', $filters->autores);
            });
        }

        if (!empty($filters->assuntos)) {
            $query->whereExists(function ($q) use ($filters) {
                $q->select(DB::raw(1))
                  ->from('livro_assunto')
                  ->whereColumn('livro_assunto.Livro_CodL', 'vw_livros_relatorio.CodL')
                  ->whereIn('livro_assunto.Assunto_CodAs', $filters->assuntos);
            });
        }

        return $query;
    }

    private function applyRangeFilter(Builder $query, string $field, ?string $value, bool $isFloat = false): void
    {
        if (empty($value)) return;

        if (str_contains($value, '-')) {
            $parts = explode('-', $value);
            if (count($parts) === 2) {
                $min = trim($parts[0]);
                $max = trim($parts[1]);
                $query->whereBetween($field, [
                    $isFloat ? (float) $min : (int) $min,
                    $isFloat ? (float) $max : (int) $max
                ]);
            }
        } elseif (str_contains($value, ',')) {
            $parts = array_map('trim', explode(',', $value));
            $query->whereIn($field, $isFloat ? array_map('floatval', $parts) : array_map('intval', $parts));
        } else {
            $val = trim($value);
            $query->where($field, '=', $isFloat ? (float) $val : (int) $val);
        }
    }
}
