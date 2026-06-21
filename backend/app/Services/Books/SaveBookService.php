<?php

namespace App\Services\Books;

use App\Exceptions\BookAlreadyExistsException;
use App\Models\Livro;
use Illuminate\Support\Facades\DB;

class SaveBookService
{
    public function execute(array $data, ?int $bookId = null): Livro
    {
        return DB::transaction(function () use ($data, $bookId) {
            $existingQuery = Livro::where('Titulo', $data['Titulo'])
                ->where('Editora', $data['Editora'])
                ->where('Edicao', $data['Edicao'] ?? 1)
                ->where('AnoPublicacao', $data['AnoPublicacao']);

            if ($bookId) {
                $existingQuery->where('CodL', '!=', $bookId);
            }

            if ($existingQuery->lockForUpdate()->exists()) {
                throw new BookAlreadyExistsException();
            }

            $livro = $bookId ? Livro::findOrFail($bookId) : new Livro();
            
            $livro->Titulo = $data['Titulo'];
            $livro->Editora = $data['Editora'];
            $livro->Edicao = $data['Edicao'] ?? 1;
            $livro->AnoPublicacao = $data['AnoPublicacao'];
            $livro->Preco = $data['Preco'];
            
            $livro->save();

            if (isset($data['autores'])) {
                $livro->autores()->sync($data['autores']);
            }
            if (isset($data['assuntos'])) {
                $livro->assuntos()->sync($data['assuntos']);
            }

            return $livro->load(['autores', 'assuntos']);
        });
    }
}
