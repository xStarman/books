<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListBooksRequest;
use App\Services\GetBookListService;
use OpenApi\Attributes as OA;

class ListBooksController extends Controller
{
    #[OA\Get(
        path: '/api/books',
        summary: 'Lista todos os livros',
        tags: ['Livros'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/PaginationPage'),
            new OA\Parameter(ref: '#/components/parameters/PaginationPageSize'),
            new OA\Parameter(name: 'filters[Titulo]', description: 'Filtrar por título', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'filters[Editora]', description: 'Filtrar por editora', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'filters[Edicao]', description: 'Filtrar por edição', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'filters[AnoPublicacao]', description: 'Filtrar por ano de publicação', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'filters[Autor]', description: 'Filtrar por autor', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'filters[Assunto]', description: 'Filtrar por assunto', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'order[CodL]', description: 'Ordenar por código', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[Titulo]', description: 'Ordenar por título', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[Editora]', description: 'Ordenar por editora', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[Edicao]', description: 'Ordenar por edição', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[AnoPublicacao]', description: 'Ordenar por ano', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order[Preco]', description: 'Ordenar por preço', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de livros paginada',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/PaginationMeta'),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(ref: '#/components/schemas/BookSchema')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function __invoke(ListBooksRequest $request, GetBookListService $service)
    {
        return $service->execute(
            $request->input('filters', []),
            $request->input('order', []),
            $request->input('page_size', 25)
        );
    }
}
