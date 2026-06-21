<?php

namespace App\Services;

use App\Models\Autor;
use Illuminate\Database\QueryException;
use App\Exceptions\AuthorHasBooksException;

class DeleteAuthorService
{
    public function execute(int $id): void
    {
        $autor = Autor::findOrFail($id);
        
        try {
            $autor->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                throw new AuthorHasBooksException();
            }
            throw $e;
        }
    }
}
