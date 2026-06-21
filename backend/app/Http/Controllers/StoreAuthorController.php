<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAuthorRequest;
use App\Services\SaveAuthorService;
use App\Exceptions\AuthorAlreadyExistsException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class StoreAuthorController extends Controller
{
    #[OA\Post(
        path: '/api/authors',
        summary: 'Cria um novo autor',
        tags: ['Autores'],
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
                response: 201,
                description: 'Autor criado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthorSchema')
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
    public function __invoke(SaveAuthorRequest $request, SaveAuthorService $service): JsonResponse
    {
        try {
            $autor = $service->execute($request->validated());
            return response()->json($autor, 201);
        } catch (AuthorAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
