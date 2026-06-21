<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;

use App\Services\Books\GetBookByIdService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GetBookByIdController extends Controller
{
    #[OA\Get(
        path: '/api/books/{id}',
        summary: 'Obtém um livro pelo código',
        tags: ['Livros'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do livro', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dados do livro',
                content: new OA\JsonContent(ref: '#/components/schemas/BookSchema')
            ),
            new OA\Response(
                response: 404,
                description: 'Livro não encontrado'
            )
        ]
    )]
    public function __invoke(int $id, GetBookByIdService $service): JsonResponse
    {
        $livro = $service->execute($id);
        return response()->json($livro);
    }
}
