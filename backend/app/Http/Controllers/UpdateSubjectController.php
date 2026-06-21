<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSubjectRequest;
use App\Services\SaveSubjectService;
use App\Exceptions\SubjectAlreadyExistsException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UpdateSubjectController extends Controller
{
    #[OA\Put(
        path: '/api/subjects/{id}',
        summary: 'Atualiza um assunto',
        tags: ['Assuntos'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do assunto', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
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
                response: 200,
                description: 'Assunto atualizado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/SubjectSchema')
            ),
            new OA\Response(
                response: 404,
                description: 'Assunto não encontrado'
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
    public function __invoke(SaveSubjectRequest $request, int $id, SaveSubjectService $service): JsonResponse
    {
        try {
            $assunto = $service->execute($request->validated(), $id);
            return response()->json($assunto);
        } catch (SubjectAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
