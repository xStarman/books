<?php

namespace App\Services\Subjects;

use App\Models\Assunto;
use App\Exceptions\SubjectAlreadyExistsException;

class SaveSubjectService
{
    public function execute(array $data, ?int $id = null): Assunto
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
