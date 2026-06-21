<?php

namespace App\Services\Authors;

use App\Models\Autor;
use App\Exceptions\AuthorAlreadyExistsException;

class SaveAuthorService
{
    public function execute(array $data, ?int $id = null): Autor
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
