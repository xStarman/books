<?php

namespace Tests\Unit\Services\Books;

use Tests\TestCase;
use App\Services\Books\SaveBookService;
use App\Models\Autor;
use App\Models\Assunto;
use App\Exceptions\BookAlreadyExistsException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SaveBookServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_throws_exception_if_book_exists()
    {
        $service = app(SaveBookService::class);
        $autor = Autor::create(['Nome' => 'Autor Mock ' . \Illuminate\Support\Str::random(4)]);
        $assunto = Assunto::create(['Descricao' => 'Assunto ' . \Illuminate\Support\Str::random(4)]);

        $payload = [
            'Titulo' => 'Livro Duplicado ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 10.50,
            'autores' => [$autor->CodAu],
            'assuntos' => [$assunto->CodAs]
        ];

        $service->execute($payload);

        $this->expectException(BookAlreadyExistsException::class);
        $service->execute($payload);
    }

    public function test_creates_book()
    {
        $service = app(SaveBookService::class);
        $autor = Autor::create(['Nome' => 'Autor ' . \Illuminate\Support\Str::random(4)]);
        $assunto = Assunto::create(['Descricao' => 'Assunto ' . \Illuminate\Support\Str::random(4)]);

        $payload = [
            'Titulo' => 'Livro Novo ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 10.50,
            'autores' => [$autor->CodAu],
            'assuntos' => [$assunto->CodAs]
        ];

        $book = $service->execute($payload);
        $this->assertEquals($payload['Titulo'], $book->Titulo);
        $this->assertDatabaseHas('livros', ['Titulo' => $payload['Titulo']]);
        $this->assertEquals(1, $book->autores()->count());
        $this->assertEquals(1, $book->assuntos()->count());
    }

    public function test_updates_book()
    {
        $service = app(SaveBookService::class);
        $autor = Autor::create(['Nome' => 'Autor ' . \Illuminate\Support\Str::random(4)]);
        $assunto = Assunto::create(['Descricao' => 'Assunto ' . \Illuminate\Support\Str::random(4)]);

        $bookToUpdate = $service->execute([
            'Titulo' => 'Livro Antigo ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 10.50,
            'autores' => [$autor->CodAu],
            'assuntos' => [$assunto->CodAs]
        ]);

        $payload = [
            'Titulo' => 'Livro Atualizado ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Nova Ed',
            'Edicao' => 2,
            'AnoPublicacao' => 2025,
            'Preco' => 20.00,
            'autores' => [$autor->CodAu],
            'assuntos' => [$assunto->CodAs]
        ];

        $updatedBook = $service->execute($payload, $bookToUpdate->CodL);
        
        $this->assertEquals($payload['Titulo'], $updatedBook->Titulo);
        $this->assertDatabaseHas('livros', ['CodL' => $bookToUpdate->CodL, 'Titulo' => $payload['Titulo']]);
    }

    public function test_creates_book_with_dynamic_authors()
    {
        $service = app(SaveBookService::class);
        $assunto = Assunto::create(['Descricao' => 'Assunto ' . \Illuminate\Support\Str::random(4)]);
        $nomeAutor = 'Autor Dinamico ' . \Illuminate\Support\Str::random(4);

        $book = $service->execute([
            'Titulo' => 'Livro Dinamico ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 15.00,
            'autores' => ["novo:{$nomeAutor}"],
            'assuntos' => [$assunto->CodAs]
        ]);

        $this->assertDatabaseHas('autores', ['Nome' => $nomeAutor]);
        $this->assertEquals(1, $book->autores()->count());
        $this->assertEquals($nomeAutor, $book->autores->first()->Nome);
    }

    public function test_creates_book_with_dynamic_subjects()
    {
        $service = app(SaveBookService::class);
        $autor = Autor::create(['Nome' => 'Autor ' . \Illuminate\Support\Str::random(4)]);
        $descAssunto = 'DinSubj ' . \Illuminate\Support\Str::random(4);

        $book = $service->execute([
            'Titulo' => 'Livro Subj Din ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 15.00,
            'autores' => [$autor->CodAu],
            'assuntos' => ["novo:{$descAssunto}"]
        ]);

        $this->assertDatabaseHas('assuntos', ['Descricao' => $descAssunto]);
        $this->assertEquals(1, $book->assuntos()->count());
        $this->assertEquals($descAssunto, $book->assuntos->first()->Descricao);
    }

    public function test_reuses_existing_author_when_dynamic_name_matches()
    {
        $service = app(SaveBookService::class);
        $autor = Autor::create(['Nome' => 'Autor Existente ' . \Illuminate\Support\Str::random(4)]);
        $assunto = Assunto::create(['Descricao' => 'Assunto ' . \Illuminate\Support\Str::random(4)]);

        $book = $service->execute([
            'Titulo' => 'Livro Reuse ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 15.00,
            'autores' => ["novo:{$autor->Nome}"],
            'assuntos' => [$assunto->CodAs]
        ]);

        $this->assertEquals($autor->CodAu, $book->autores->first()->CodAu);
    }

    public function test_creates_book_with_mixed_existing_and_dynamic_authors()
    {
        $service = app(SaveBookService::class);
        $autorExistente = Autor::create(['Nome' => 'Existente ' . \Illuminate\Support\Str::random(4)]);
        $assunto = Assunto::create(['Descricao' => 'Assunto ' . \Illuminate\Support\Str::random(4)]);
        $nomeNovo = 'Novo Misto ' . \Illuminate\Support\Str::random(4);

        $book = $service->execute([
            'Titulo' => 'Livro Misto ' . \Illuminate\Support\Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 15.00,
            'autores' => [$autorExistente->CodAu, "novo:{$nomeNovo}"],
            'assuntos' => [$assunto->CodAs]
        ]);

        $this->assertEquals(2, $book->autores()->count());
        $this->assertDatabaseHas('autores', ['Nome' => $nomeNovo]);
    }
}
