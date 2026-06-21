<?php

namespace App\Services;

use App\Models\Autor;

class GetAuthorByIdService
{
    public function execute(int $id): Autor
    {
        return Autor::findOrFail($id);
    }
}
