<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;

use App\Services\Books\DeleteBookService;
use OpenApi\Attributes as OA;

class DeleteBookController extends Controller
{
    #[OA\Delete(
        path: '/api/books/{id}',
        summary: 'Exclui um livro pelo código',
        tags: ['Livros'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do livro', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Livro excluído com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Livro não encontrado'
            )
        ]
    )]
    public function __invoke(int $id, DeleteBookService $service)
    {
        $service->execute($id);

        return response()->json([
            'message' => 'Livro excluído com sucesso.'
        ], 200);
    }
}
