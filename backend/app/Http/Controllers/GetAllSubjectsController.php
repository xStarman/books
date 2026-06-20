<?php

namespace App\Http\Controllers;

use App\Services\GetAllSubjectsService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class GetAllSubjectsController extends Controller
{
    #[OA\Get(
        path: '/api/subjects/all',
        summary: 'Lista todos os assuntos (sem paginação)',
        tags: ['Assuntos'],
        parameters: [
            new OA\Parameter(name: 'filters[Descricao]', description: 'Filtrar por descrição', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'order[CodAs]', description: 'Ordenar por código', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[Descricao]', description: 'Ordenar por descrição', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista completa de assuntos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/SubjectSchema')
                )
            )
        ]
    )]
    public function __invoke(Request $request, GetAllSubjectsService $service)
    {
        return response()->json(
            $service->execute(
                $request->input('filters', []),
                $request->input('order', [])
            )
        );
    }
}
