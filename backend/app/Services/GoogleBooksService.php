<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class GoogleBooksService
{
    public function fetchByIsbn(string $isbn): array
    {
        $url = env('GOOGLE_BOOKS_URL', 'https://www.googleapis.com/books');
        $key = env('GOOGLE_BOOKS_KEY');
        try {

            $response = Http::get("{$url}/v1/volumes", [
                'q' => "isbn:{$isbn}",
                'key' => $key,
            ]);
            $response->throw();

            return $response->json();
        } catch (RequestException $th) {
            $data = [];
            if ($th->hasResponse()) {
                $response = $th->getResponse();
                $errorBody = $response->getBody()->getContents();
                $data = json_decode($errorBody, true);
            }

            Log::error($th->getMessage(), ["trace" => $th->getTrace(), "response" => $data]);
            throw new \Exception('Falha ao comunicar com a API do Google Books.');
        }
    }
}
