<?php

namespace App\Services\Subjects;

use App\Models\Assunto;
use App\Repositories\Subjects\SubjectRepository;

class SaveSubjectService
{
    public function __construct(private SubjectRepository $subjectRepository)
    {
    }

    public function execute(array $data, ?int $id = null): Assunto
    {
        return $this->subjectRepository->save($data, $id);
    }
}
