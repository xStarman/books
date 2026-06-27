<?php

namespace App\Services\Isbn;

use App\Repositories\Isbn\IsbnRepository;

class GetBookByIsbnService
{
    public function __construct(private IsbnRepository $isbnRepository)
    {
    }

    public function execute(string $isbn): ?array
    {
        $data = $this->isbnRepository->getByIsbn($isbn);

        if (empty($data['items'])) {
            return null;
        }

        $item = $data['items'][0];
        $volumeInfo = $item['volumeInfo'] ?? [];

        return [
            'Titulo' => $volumeInfo['title'] ?? null,
            'Editora' => $volumeInfo['publisher'] ?? null,
            'AnoPublicacao' => isset($volumeInfo['publishedDate']) ? substr($volumeInfo['publishedDate'], 0, 4) : null,
            'autores' => $volumeInfo['authors'] ?? [],
            'assuntos' => $volumeInfo['categories'] ?? [],
            'descricao' => $volumeInfo['description'] ?? null,
            'thumbnail' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
        ];
    }
}
