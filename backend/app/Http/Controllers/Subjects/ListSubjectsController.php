<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;

use App\Http\Requests\Subjects\ListSubjectsRequest;
use App\Services\Subjects\GetSubjectListService;
use OpenApi\Attributes as OA;

class ListSubjectsController extends Controller
{
    #[OA\Get(
        path: '/api/subjects',
        summary: 'Lista todos os assuntos',
        tags: ['Assuntos'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/PaginationPage'),
            new OA\Parameter(ref: '#/components/parameters/PaginationPageSize'),
            new OA\Parameter(name: 'filters[Descricao]', description: 'Filtrar por descrição', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'order[CodAs]', description: 'Ordenar por código', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[Descricao]', description: 'Ordenar por descrição', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de assuntos paginada',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/PaginationMeta'),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(ref: '#/components/schemas/SubjectSchema')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function __invoke(ListSubjectsRequest $request, GetSubjectListService $service)
    {
        return $service->execute(
            $request->input('filters', []),
            $request->input('order', []),
            $request->input('page_size', 25)
        );
    }
}
