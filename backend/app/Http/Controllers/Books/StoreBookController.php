<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;

use App\Http\Requests\Books\SaveBookRequest;
use App\Services\Books\SaveBookService;
use App\Exceptions\BookAlreadyExistsException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class StoreBookController extends Controller
{
    #[OA\Post(
        path: '/api/books',
        summary: 'Cria um novo livro',
        tags: ['Livros'],
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
                response: 201,
                description: 'Livro criado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/BookSchema')
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
    public function __invoke(SaveBookRequest $request, SaveBookService $service): JsonResponse
    {
        try {
            $livro = $service->execute($request->validated());
            return response()->json($livro, 201);
        } catch (BookAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
