<?php

namespace App\Http\Controllers\Isbn;

use App\Http\Controllers\Controller;
use App\Services\Isbn\GetBookByIsbnService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GetIsbnController extends Controller
{
    #[OA\Get(
        path: '/api/isbn/{isbn}',
        summary: 'Obtém dados de um livro pelo ISBN',
        tags: ['Isbn'],
        parameters: [
            new OA\Parameter(name: 'isbn', description: 'Código ISBN', in: 'path', required: true, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dados do livro'
            ),
            new OA\Response(
                response: 404,
                description: 'Livro não encontrado'
            )
        ]
    )]
    public function __invoke(string $isbn, GetBookByIsbnService $service): JsonResponse
    {
        try {
            $livro = $service->execute($isbn);

            if (!$livro) {
                return response()->json(['message' => 'Livro não encontrado'], 404);
            }

            return response()->json($livro);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
