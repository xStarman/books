<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSubjectRequest;
use App\Services\SaveSubjectService;
use App\Exceptions\SubjectAlreadyExistsException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class StoreSubjectController extends Controller
{
    #[OA\Post(
        path: '/api/subjects',
        summary: 'Cria um novo assunto',
        tags: ['Assuntos'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'Descricao', type: 'string', maxLength: 20)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Assunto criado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/SubjectSchema')
            ),
            new OA\Response(
                response: 409,
                description: 'Conflito de dados (ex: assunto já existente)',
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
    public function __invoke(SaveSubjectRequest $request, SaveSubjectService $service): JsonResponse
    {
        try {
            $assunto = $service->execute($request->validated());
            return response()->json($assunto, 201);
        } catch (SubjectAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
