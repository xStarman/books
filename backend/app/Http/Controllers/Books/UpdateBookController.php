<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;

use App\Http\Requests\Books\SaveBookRequest;
use App\Services\Books\SaveBookService;
use App\Exceptions\BookAlreadyExistsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UpdateBookController extends Controller
{
    #[OA\Put(
        path: '/api/books/{id}',
        summary: 'Atualiza um livro',
        tags: ['Livros'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Código do livro', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'Titulo', type: 'string', maxLength: 40),
                    new OA\Property(property: 'Editora', type: 'string', maxLength: 40),
                    new OA\Property(property: 'Edicao', type: 'integer'),
                    new OA\Property(property: 'AnoPublicacao', type: 'integer'),
                    new OA\Property(property: 'Preco', type: 'number', format: 'float'),
                    new OA\Property(property: 'autores', type: 'array', items: new OA\Items(type: 'integer')),
                    new OA\Property(property: 'assuntos', type: 'array', items: new OA\Items(type: 'integer'))
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Livro atualizado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/BookSchema')
            ),
            new OA\Response(
                response: 404,
                description: 'Livro não encontrado'
            ),
            new OA\Response(
                response: 409,
                description: 'Conflito de dados (ex: livro já existente)',
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
    public function __invoke(SaveBookRequest $request, int $id, SaveBookService $service): JsonResponse
    {
        try {
            $livro = $service->execute($request->validated(), $id);
            return response()->json($livro, 200);
        } catch (BookAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
