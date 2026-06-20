<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveBookRequest;
use App\Services\SaveBookService;
use App\Exceptions\BookAlreadyExistsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class UpdateBookController extends Controller
{
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
