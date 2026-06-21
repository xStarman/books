<?php

namespace Tests\Unit\Services\Subjects;

use Tests\TestCase;
use App\Services\Subjects\GetSubjectByIdService;
use App\Models\Assunto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetSubjectByIdServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_get_subject_by_id()
    {
        $subject = Assunto::create(['Descricao' => 'Assunto Teste']);
        $service = app(GetSubjectByIdService::class);
        
        $result = $service->execute($subject->CodAs);
        
        $this->assertEquals($subject->CodAs, $result->CodAs);
        $this->assertEquals('Assunto Teste', $result->Descricao);
    }

    public function test_throws_exception_if_subject_not_found()
    {
        $service = app(GetSubjectByIdService::class);
        
        $this->expectException(ModelNotFoundException::class);
        $service->execute(999999);
    }
}
