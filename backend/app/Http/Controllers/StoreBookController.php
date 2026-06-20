<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveBookRequest;
use App\Services\SaveBookService;
use App\Exceptions\BookAlreadyExistsException;
use Illuminate\Http\JsonResponse;

class StoreBookController extends Controller
{
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
