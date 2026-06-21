<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;

use App\Services\Subjects\DeleteSubjectService;
use App\Exceptions\SubjectHasBooksException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DeleteSubjectController extends Controller
{
    #[OA\Delete(
        path: '/api/subjects/{id}',
        summary: 'Exclui um assunto pelo código',
        tags: ['Assuntos'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do assunto', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Assunto excluído com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Assunto não encontrado'
            ),
            new OA\Response(
                response: 409,
                description: 'Assunto possui livros vinculados'
            )
        ]
    )]
    public function __invoke(int $id, DeleteSubjectService $service): JsonResponse
    {
        try {
            $service->execute($id);

            return response()->json([
                'message' => 'Assunto excluído com sucesso.'
            ], 200);
        } catch (SubjectHasBooksException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 409);
        }
    }
}
