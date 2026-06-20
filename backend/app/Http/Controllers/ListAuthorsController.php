<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListAuthorsRequest;
use App\Services\GetAuthorListService;
use OpenApi\Attributes as OA;

class ListAuthorsController extends Controller
{
    #[OA\Get(
        path: '/api/authors',
        summary: 'Lista todos os autores',
        tags: ['Autores'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/PaginationPage'),
            new OA\Parameter(ref: '#/components/parameters/PaginationPageSize'),
            new OA\Parameter(name: 'filters[Nome]', description: 'Filtrar por nome', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'order[CodAu]', description: 'Ordenar por código', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[Nome]', description: 'Ordenar por nome', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de autores paginada',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/PaginationMeta'),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(ref: '#/components/schemas/AuthorSchema')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function __invoke(ListAuthorsRequest $request, GetAuthorListService $service)
    {
        return $service->execute(
            $request->input('filters', []),
            $request->input('order', []),
            $request->input('page_size', 25)
        );
    }
}
