<?php
namespace Tests\Unit\Services\Authors;

use Tests\TestCase;
use App\Services\Authors\SaveAuthorService;
use App\Models\Autor;
use App\Exceptions\AuthorAlreadyExistsException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class SaveAuthorServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_throws_exception_if_author_exists_on_create()
    {
        $service = app(SaveAuthorService::class);
        $payload = ['Nome' => 'Autor Duplicado ' . Str::random(8)];
        $service->execute($payload);
        
        $this->expectException(AuthorAlreadyExistsException::class);
        $service->execute($payload);
    }

    public function test_throws_exception_if_author_exists_on_update()
    {
        $service = app(SaveAuthorService::class);
        $nome = 'Autor Existente ' . Str::random(8);
        Autor::create(['Nome' => $nome]);
        
        $authorToUpdate = Autor::create(['Nome' => 'Outro Autor ' . Str::random(8)]);
        
        $this->expectException(AuthorAlreadyExistsException::class);
        $service->execute(['Nome' => $nome], $authorToUpdate->CodAu);
    }

    public function test_creates_author()
    {
        $service = app(SaveAuthorService::class);
        $nome = 'Novo Autor Único ' . Str::random(8);
        $author = $service->execute(['Nome' => $nome]);
        
        $this->assertEquals($nome, $author->Nome);
        $this->assertDatabaseHas('autores', ['Nome' => $nome]);
    }

    public function test_updates_author()
    {
        $service = app(SaveAuthorService::class);
        $author = Autor::create(['Nome' => 'Autor Velho ' . Str::random(8)]);
        $nomeNovo = 'Autor Atualizado ' . Str::random(8);
        
        $updatedAuthor = $service->execute(['Nome' => $nomeNovo], $author->CodAu);
        
        $this->assertEquals($nomeNovo, $updatedAuthor->Nome);
        $this->assertDatabaseHas('autores', ['CodAu' => $author->CodAu, 'Nome' => $nomeNovo]);
    }
}