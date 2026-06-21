<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAuthorRequest;
use App\Services\SaveAuthorService;
use App\Exceptions\AuthorAlreadyExistsException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UpdateAuthorController extends Controller
{
    #[OA\Put(
        path: '/api/authors/{id}',
        summary: 'Atualiza um autor',
        tags: ['Autores'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do autor', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'Nome', type: 'string', maxLength: 40)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Autor atualizado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthorSchema')
            ),
            new OA\Response(
                response: 404,
                description: 'Autor não encontrado'
            ),
            new OA\Response(
                response: 409,
                description: 'Conflito de dados (ex: autor já existente)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação'
            )
        ]
    )]
    public function __invoke(SaveAuthorRequest $request, int $id, SaveAuthorService $service): JsonResponse
    {
        try {
            $autor = $service->execute($request->validated(), $id);
            return response()->json($autor);
        } catch (AuthorAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
