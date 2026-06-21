<?php

namespace App\Services\Books;

use App\Models\Livro;

class DeleteBookService
{
    public function execute(int $id): void
    {
        $livro = Livro::findOrFail($id);
        $livro->delete();
    }
}
