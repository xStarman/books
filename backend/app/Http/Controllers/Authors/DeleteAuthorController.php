<?php

namespace App\Http\Controllers\Authors;

use App\Http\Controllers\Controller;

use App\Services\Authors\DeleteAuthorService;
use App\Exceptions\AuthorHasBooksException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DeleteAuthorController extends Controller
{
    #[OA\Delete(
        path: '/api/authors/{id}',
        summary: 'Exclui um autor pelo código',
        tags: ['Autores'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do autor', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Autor excluído com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Autor não encontrado'
            ),
            new OA\Response(
                response: 409,
                description: 'Autor possui livros vinculados'
            )
        ]
    )]
    public function __invoke(int $id, DeleteAuthorService $service): JsonResponse
    {
        try {
            $service->execute($id);

            return response()->json([
                'message' => 'Autor excluído com sucesso.'
            ], 200);
        } catch (AuthorHasBooksException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 409);
        }
    }
}
