<?php

namespace Tests\Unit\Services\Books;

use Tests\TestCase;
use App\Services\Books\DeleteBookService;
use App\Models\Livro;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteBookServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_delete_book()
    {
        $book = Livro::create([
            'Titulo' => 'Livro Delete ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 10.50,
        ]);
        
        $service = app(DeleteBookService::class);
        
        $service->execute($book->CodL);
        
        $this->assertDatabaseMissing('livros', ['CodL' => $book->CodL]);
    }

    public function test_throws_exception_if_book_not_found()
    {
        $service = app(DeleteBookService::class);
        
        $this->expectException(ModelNotFoundException::class);
        $service->execute(999999);
    }
}
