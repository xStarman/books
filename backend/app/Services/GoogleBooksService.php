<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    public function fetchByIsbn(string $isbn): array
    {
        $url = env('GOOGLE_BOOKS_URL', 'https://www.googleapis.com/books');
        $key = env('GOOGLE_BOOKS_KEY');

        $response = Http::get("{$url}/v1/volumes", [
            'q' => "isbn:{$isbn}",
            'key' => $key,
        ]);

        if ($response->failed()) {
            throw new \Exception('Falha ao comunicar com a API do Google Books.');
        }

        return $response->json();
    }
}
