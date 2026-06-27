<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;
use Exception;

class GoogleBooksService
{
    public function fetchByIsbn(string $isbn): array
    {
        $url = env('GOOGLE_BOOKS_URL', 'https://www.googleapis.com');
        $key = env('GOOGLE_BOOKS_KEY');
        try {

            $response = Http::get("{$url}/books/v1/volumes", [
                'q' => "isbn:{$isbn}",
                'key' => $key,
            ]);
            $response->throw();

            return $response->json();
        } catch (RequestException $th) {
            $data = $th->response ? $th->response->json() : [];
            $request = $th->response?->transferStats?->getRequest();
            $requestUrl = $request ? (string) $request->getUri() : "{$url}/v1/volumes?q=isbn:{$isbn}";
            $requestHeaders = $request ? $request->getHeaders() : [];

            Log::error($th->getMessage(), [
                "request_url" => $requestUrl,
                "request_headers" => $requestHeaders,
                "response" => $data,
                "trace" => $th->getTraceAsString()
            ]);
            throw new Exception('Falha ao comunicar com a API do Google Books.');
        } catch (Exception $th) {
            Log::error($th->getMessage(), ["trace" => $th->getTraceAsString()]);
            throw new Exception('Falha ao comunicar com a API do Google Books.');
        }
    }
}
