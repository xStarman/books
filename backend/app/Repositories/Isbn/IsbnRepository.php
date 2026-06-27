<?php

namespace App\Repositories\Isbn;

use App\Services\GoogleBooksService;

class IsbnRepository
{
    public function __construct(private GoogleBooksService $googleBooksService)
    {
    }

    public function getByIsbn(string $isbn): array
    {
        return $this->googleBooksService->fetchByIsbn($isbn);
    }
}
