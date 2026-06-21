<?php

namespace Tests\Unit\Services\Subjects;

use Tests\TestCase;
use App\Services\Subjects\SaveSubjectService;
use App\Models\Assunto;
use App\Exceptions\SubjectAlreadyExistsException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SaveSubjectServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_create_subject()
    {
        $service = app(SaveSubjectService::class);
        $payload = ['Descricao' => 'Assunto Teste Create'];

        $subject = $service->execute($payload);

        $this->assertInstanceOf(Assunto::class, $subject);
        $this->assertEquals('Assunto Teste Create', $subject->Descricao);
        $this->assertDatabaseHas('assuntos', ['Descricao' => 'Assunto Teste Create']);
    }

    public function test_can_update_subject()
    {
        $subject = Assunto::create(['Descricao' => 'Assunto Antigo']);
        $service = app(SaveSubjectService::class);
        $payload = ['Descricao' => 'Assunto Novo'];

        $updatedSubject = $service->execute($payload, $subject->CodAs);

        $this->assertEquals('Assunto Novo', $updatedSubject->Descricao);
        $this->assertDatabaseHas('assuntos', ['CodAs' => $subject->CodAs, 'Descricao' => 'Assunto Novo']);
    }

    public function test_throws_exception_if_subject_exists_on_create()
    {
        Assunto::create(['Descricao' => 'Assunto Duplicado']);
        $service = app(SaveSubjectService::class);
        $payload = ['Descricao' => 'Assunto Duplicado'];

        $this->expectException(SubjectAlreadyExistsException::class);
        $service->execute($payload);
    }

    public function test_throws_exception_if_subject_exists_on_update()
    {
        Assunto::create(['Descricao' => 'Assunto Existente']);
        $subjectToUpdate = Assunto::create(['Descricao' => 'Assunto Update']);
        $service = app(SaveSubjectService::class);
        $payload = ['Descricao' => 'Assunto Existente'];

        $this->expectException(SubjectAlreadyExistsException::class);
        $service->execute($payload, $subjectToUpdate->CodAs);
    }
}
