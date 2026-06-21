<?php

namespace Tests\Unit\Services\Subjects;

use Tests\TestCase;
use App\Services\Subjects\DeleteSubjectService;
use App\Models\Assunto;
use App\Models\Livro;
use App\Models\Autor;
use App\Exceptions\SubjectHasBooksException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteSubjectServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_delete_subject()
    {
        $subject = Assunto::create(['Descricao' => 'Assunto Delete']);
        $service = app(DeleteSubjectService::class);
        
        $service->execute($subject->CodAs);
        
        $this->assertDatabaseMissing('assuntos', ['CodAs' => $subject->CodAs]);
    }

    public function test_throws_exception_if_subject_not_found()
    {
        $service = app(DeleteSubjectService::class);
        
        $this->expectException(ModelNotFoundException::class);
        $service->execute(999999);
    }

    public function test_throws_exception_if_subject_has_books()
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

        $service = app(DeleteSubjectService::class);
        
        $this->expectException(SubjectHasBooksException::class);
        $service->execute($subject->CodAs);
    }
}
