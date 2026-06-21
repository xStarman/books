<?php

namespace App\Services\Books;

use App\Models\Livro;

class GetBookByIdService
{
    public function execute(int $id): Livro
    {
        return Livro::with(['autores', 'assuntos'])->findOrFail($id);
    }
}
