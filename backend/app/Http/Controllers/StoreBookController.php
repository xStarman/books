<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveBookRequest;
use App\Services\SaveBookService;
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
            content: new OA\JsonContent(ref: '#/components/schemas/SaveBookRequest')
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
