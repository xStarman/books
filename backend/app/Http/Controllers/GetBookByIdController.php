<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetBookByIdController extends Controller
{
    public function __invoke(int $id): JsonResponse
    {
        $livro = Livro::with(['autores', 'assuntos'])->findOrFail($id);
        return response()->json($livro);
    }
}
