<?php

namespace Tests\Unit\Services\Books;

use Tests\TestCase;
use App\Services\Books\GetBookByIdService;
use App\Models\Livro;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetBookByIdServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_get_book_by_id()
    {
        $book = Livro::create([
            'Titulo' => 'Livro Teste ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 10.50,
        ]);
        
        $service = app(GetBookByIdService::class);
        
        $result = $service->execute($book->CodL);
        
        $this->assertEquals($book->CodL, $result->CodL);
        $this->assertEquals($book->Titulo, $result->Titulo);
    }

    public function test_throws_exception_if_book_not_found()
    {
        $service = app(GetBookByIdService::class);
        
        $this->expectException(ModelNotFoundException::class);
        $service->execute(999999);
    }
}
