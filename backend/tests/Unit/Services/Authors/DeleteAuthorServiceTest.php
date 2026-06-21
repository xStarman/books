<?php

namespace Tests\Unit\Services\Authors;

use Tests\TestCase;
use App\Services\Authors\DeleteAuthorService;
use App\Models\Assunto;
use App\Models\Livro;
use App\Models\Autor;
use App\Exceptions\AuthorHasBooksException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteAuthorServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_delete_author()
    {
        $author = Autor::create(['Nome' => 'Autor Delete']);
        $service = app(DeleteAuthorService::class);
        
        $service->execute($author->CodAu);
        
        $this->assertDatabaseMissing('autores', ['CodAu' => $author->CodAu]);
    }

    public function test_throws_exception_if_author_not_found()
    {
        $service = app(DeleteAuthorService::class);
        
        $this->expectException(ModelNotFoundException::class);
        $service->execute(999999);
    }

    public function test_throws_exception_if_author_has_books()
    {
        $subject = Assunto::create(['Descricao' => 'Assunto Book']);
        $author = Autor::create(['Nome' => 'Autor Book']);
        $book = Livro::create([
            'Titulo' => 'Livro Test',
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 10.0
        ]);
        
        $book->assuntos()->attach($subject->CodAs);
        $book->autores()->attach($author->CodAu);

        $service = app(DeleteAuthorService::class);
        
        $this->expectException(AuthorHasBooksException::class);
        $service->execute($author->CodAu);
    }
}
