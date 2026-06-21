<?php

namespace App\Http\Controllers\Authors;

use App\Http\Controllers\Controller;

use App\Services\Authors\GetAuthorByIdService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GetAuthorByIdController extends Controller
{
    #[OA\Get(
        path: '/api/authors/{id}',
        summary: 'Obtém um autor pelo código',
        tags: ['Autores'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do autor', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dados do autor',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthorSchema')
            ),
            new OA\Response(
                response: 404,
                description: 'Autor não encontrado'
            )
        ]
    )]
    public function __invoke(int $id, GetAuthorByIdService $service): JsonResponse
    {
        $autor = $service->execute($id);
        return response()->json($autor);
    }
}
