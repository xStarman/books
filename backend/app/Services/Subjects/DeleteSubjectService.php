<?php

namespace App\Services\Subjects;

use App\Models\Assunto;
use Illuminate\Database\QueryException;
use App\Exceptions\SubjectHasBooksException;

class DeleteSubjectService
{
    public function execute(int $id): void
    {
        $assunto = Assunto::findOrFail($id);
        
        try {
            $assunto->delete();
        } catch (QueryException $e) {
            if (in_array($e->getCode(), ['23503', '23001'])) {
                throw new SubjectHasBooksException();
            }
            throw $e;
        }
    }
}
