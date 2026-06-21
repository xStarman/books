<?php

namespace App\Services;

use App\Models\Assunto;

class GetSubjectByIdService
{
    public function execute(int $id): Assunto
    {
        return Assunto::findOrFail($id);
    }
}
