<?php

namespace App\Http\Controllers;

use App\Services\GetAllAuthorsService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class GetAllAuthorsController extends Controller
{
    #[OA\Get(
        path: '/api/authors/all',
        summary: 'Lista todos os autores (sem paginação)',
        tags: ['Autores'],
        parameters: [
            new OA\Parameter(name: 'filters[Nome]', description: 'Filtrar por nome', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'order[CodAu]', description: 'Ordenar por código', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[Nome]', description: 'Ordenar por nome', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista completa de autores',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/AuthorSchema')
                )
            )
        ]
    )]
    public function __invoke(Request $request, GetAllAuthorsService $service)
    {
        return response()->json(
            $service->execute(
                $request->input('filters', []),
                $request->input('order', [])
            )
        );
    }
}
