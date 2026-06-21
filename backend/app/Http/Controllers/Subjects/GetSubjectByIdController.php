<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;

use App\Services\Subjects\GetSubjectByIdService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GetSubjectByIdController extends Controller
{
    #[OA\Get(
        path: '/api/subjects/{id}',
        summary: 'Obtém um assunto pelo código',
        tags: ['Assuntos'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do assunto', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dados do assunto',
                content: new OA\JsonContent(ref: '#/components/schemas/SubjectSchema')
            ),
            new OA\Response(
                response: 404,
                description: 'Assunto não encontrado'
            )
        ]
    )]
    public function __invoke(int $id, GetSubjectByIdService $service): JsonResponse
    {
        $assunto = $service->execute($id);
        return response()->json($assunto);
    }
}
