<?php

namespace App\Services\Authors;

use App\Models\Autor;
use App\Repositories\Authors\AuthorRepository;

class SaveAuthorService
{
    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    public function execute(array $data, ?int $id = null): Autor
    {
        return $this->authorRepository->save($data, $id);
    }
}
